import cv2
import keras.api
import numpy as np
from keras.api.models import load_model

class EmotionPredictor:
    def __init__(self, target_size=(48, 48)):
        """
        Inicializa el predictor de emociones sin cargar un modelo.
        :param target_size: Tamaño al que se redimensionarán las imágenes (48x48 para FER2013).
        """
        self.model = None  # Inicializa sin modelo
        self.target_size = target_size
        self.class_names = ['angry', 'disgust', 'fear', 'happy', 'neutral', 'sad', 'surprise']  # Orden alfabético

    def load_model(self, model_path):
        """Carga el modelo desde la ruta especificada."""
        self.model = load_model(model_path)

    def preprocess_image(self, image):
        """Preprocesa la imagen para la predicción."""
        image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)  # Convertir a escala de grises
        image = cv2.resize(image, self.target_size)  # Redimensionar
        image = image / 255.0  # Normalizar
        image = np.expand_dims(image, axis=-1)  # Añadir dimensión para el canal
        return np.expand_dims(image, axis=0)  # Añadir dimensión para el batch

    def predict(self, image):
        """Realiza la predicción de emociones en una imagen."""
        processed_image = self.preprocess_image(image)
        predictions = self.model.predict(processed_image)
        return predictions

    def display_prediction(self, image):
        """Muestra las probabilidades de predicción para una imagen dada."""
        # Preprocesar la imagen
        processed_image = self.preprocess_image(image)
        # Asegurarse de que la forma sea correcta
        processed_image = np.squeeze(processed_image)  # Eliminar dimensiones innecesarias
        predictions = self.model.predict(np.expand_dims(processed_image, axis=0))  # Añadir dimensión para el batch
        # Obtener las probabilidades y las clases
        pred_probs = predictions[0]
        for i, (class_name, prob) in enumerate(zip(self.class_names, pred_probs)):
            print(f"{class_name}: {prob * 100:.2f}%")  # Mostrar todas las emociones con sus porcentajes
        # Mostrar la emoción más probable
        true_label = np.argmax(pred_probs)
        print(
            f"\nEmoción más probable: {self.class_names[true_label]} con {pred_probs[true_label] * 100:.2f}% de probabilidad.")

    def predict_from_webcam(self):
        """Realiza predicciones en tiempo real utilizando la cámara web."""
        cap = cv2.VideoCapture(1)  # Iniciar la cámara web

        while True:
            ret, frame = cap.read()
            if not ret:
                break

            # Realizar la predicción
            predictions = self.predict(frame)
            pred_probs = predictions[0]
            max_index = np.argmax(pred_probs)
            emotion = self.class_names[max_index]
            confidence = pred_probs[max_index] * 100

            # Mostrar la predicción en el marco
            cv2.putText(frame, f"{emotion}: {confidence:.2f}%", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 255), 2)
            cv2.imshow("Webcam", frame)

            if cv2.waitKey(1) & 0xFF == ord('q'):  # Presionar 'q' para salir
                break

        cap.release()
        cv2.destroyAllWindows()
