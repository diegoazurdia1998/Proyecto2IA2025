import os
import cv2
import numpy as np
from sklearn.naive_bayes import GaussianNB
from sklearn.model_selection import train_test_split
from sklearn.metrics import classification_report
import joblib
from tqdm import tqdm


class EmotionTrainer:
    def __init__(self, data_path="archive"):
        self.data_path = data_path
        self.emotions = ["angry", "disgust", "fear", "happy", "neutral", "sad", "surprise"]
        self.model = GaussianNB()
        # Cargar modelos Haar Cascade una vez durante la inicialización
        self.face_cascade = cv2.CascadeClassifier(
            cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        self.eye_cascade = cv2.CascadeClassifier(
            cv2.data.haarcascades + 'haarcascade_eye.xml')

    def load_images(self, subset="train"):
        """Carga imágenes y etiquetas desde las subcarpetas"""
        images = []
        labels = []

        subset_path = os.path.join(self.data_path, subset)

        for i, emotion in enumerate(self.emotions):
            emotion_path = os.path.join(subset_path, emotion)
            print(f"Cargando imágenes de {emotion}...")

            for img_file in tqdm(os.listdir(emotion_path)):
                img_path = os.path.join(emotion_path, img_file)
                try:
                    img = cv2.imread(img_path, cv2.IMREAD_GRAYSCALE)
                    if img is None:
                        continue

                    # Preprocesamiento completo
                    processed_img = self.full_preprocess(img)
                    if processed_img is not None:
                        images.append(processed_img.flatten())
                        labels.append(i)
                except Exception as e:
                    print(f"Error procesando {img_path}: {str(e)}")
                    continue

        return np.array(images), np.array(labels)

    def full_preprocess(self, image):
        """Pipeline completo de preprocesamiento para una imagen"""
        try:
            # Convertir a color si es necesario para la detección
            if len(image.shape) == 2:
                color_img = cv2.cvtColor(image, cv2.COLOR_GRAY2BGR)
            else:
                color_img = image.copy()

            # 1. Detección de rostros
            faces = self.detect_faces(color_img)
            if not faces:
                return None

            # 2. Selección del rostro principal
            (x, y, w, h) = faces[0]

            # 3. Evaluación de calidad
            quality = self.assess_face_quality((x, y, w, h), color_img)
            if not quality['is_acceptable']:
                print(f"Advertencia: Calidad de rostro subóptima (Enfoque: {quality['focus_score']:.1f})")

            # 4. Recorte y preprocesamiento final
            face_roi = image[y:y + h, x:x + w] if len(image.shape) == 2 else color_img[y:y + h, x:x + w]

            # 5. Alineación básica sin dlib
            aligned_face = self.align_face_simple(face_roi)

            # 6. Preprocesamiento final
            processed = cv2.resize(aligned_face, (48, 48))
            processed = cv2.equalizeHist(processed)
            return processed.astype('float32') / 255.0

        except Exception as e:
            print(f"Error en preprocesamiento: {str(e)}")
            return None

    def detect_faces(self, image):
        """Detección de rostros usando solo OpenCV"""
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        faces = self.face_cascade.detectMultiScale(
            gray,
            scaleFactor=1.1,
            minNeighbors=5,
            minSize=(48, 48),
            flags=cv2.CASCADE_SCALE_IMAGE
        )

        if len(faces) > 0:
            # Ordenar por área (mayor a menor) y devolver solo el rostro principal
            faces = sorted(faces, key=lambda x: x[2] * x[3], reverse=True)
            return faces
        return []

    def assess_face_quality(self, face_region, image):
        """Evaluación de calidad del rostro sin dlib"""
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        x, y, w, h = face_region
        face_roi = gray[y:y + h, x:x + w]

        # 1. Medición de enfoque
        fm = cv2.Laplacian(face_roi, cv2.CV_64F).var()
        focus_ok = fm > 80  # Umbral más bajo que antes

        # 2. Verificación de iluminación
        hist = cv2.calcHist([face_roi], [0], None, [256], [0, 256])
        brightness = np.sum(hist[150:]) / np.sum(hist) if np.sum(hist) > 0 else 0
        lighting_ok = brightness > 0.15  # Umbral más bajo

        # 3. Detección de ojos (sin dlib)
        eyes = self.eye_cascade.detectMultiScale(face_roi)
        occlusion_ok = len(eyes) >= 1  # Al menos un ojo visible

        return {
            'focus_score': fm,
            'brightness_score': brightness,
            'occlusion_ok': occlusion_ok,
            'is_acceptable': focus_ok or lighting_ok  # Umbrales más flexibles
        }

    def align_face_simple(self, face_image):
        """Alineación básica usando solo OpenCV"""
        if len(face_image.shape) == 3:
            gray = cv2.cvtColor(face_image, cv2.COLOR_BGR2GRAY)
        else:
            gray = face_image.copy()

        eyes = self.eye_cascade.detectMultiScale(gray)

        if len(eyes) >= 2:
            # Tomar los dos primeros ojos detectados
            (ex1, ey1, ew1, eh1), (ex2, ey2, ew2, eh2) = eyes[:2]

            # Calcular centro de los ojos
            eye1_center = (ex1 + ew1 // 2, ey1 + eh1 // 2)
            eye2_center = (ex2 + ew2 // 2, ey2 + eh2 // 2)

            # Calcular ángulo entre ojos
            dY = eye2_center[1] - eye1_center[1]
            dX = eye2_center[0] - eye1_center[0]
            angle = np.degrees(np.arctan2(dY, dX))

            # Rotar la imagen
            M = cv2.getRotationMatrix2D((face_image.shape[1] // 2, face_image.shape[0] // 2), angle, 1)
            rotated = cv2.warpAffine(face_image, M, (face_image.shape[1], face_image.shape[0]))
            return rotated

        return face_image  # Devolver original si no se puede alinear

    def preprocess_data(self, X):
        """Normaliza los datos de píxeles"""
        return X / 255.0

    def train(self, save_model=True):
        """Entrena el modelo y opcionalmente lo guarda"""
        X, y = self.load_images("train")

        # Verificar que tenemos datos
        if len(X) == 0:
            raise ValueError("No se pudieron cargar imágenes para entrenamiento")

        X = self.preprocess_data(X)

        # Dividir en entrenamiento y validación
        X_train, X_val, y_train, y_val = train_test_split(
            X, y, test_size=0.2, random_state=42)

        print("Entrenando modelo...")
        self.model.fit(X_train, y_train)

        # Evaluación
        val_pred = self.model.predict(X_val)
        print("\nReporte de Clasificación:")
        print(classification_report(y_val, val_pred, target_names=self.emotions))

        if save_model:
            os.makedirs("model", exist_ok=True)
            joblib.dump(self.model, "model/emotion_classifier_1.joblib")
            print("\nModelo guardado en model/emotion_classifier.joblib")

        return self.model

if __name__ == "__main__":
    print("\nIniciando entrenamiento...")
    trainer = EmotionTrainer()
    trainer.train()