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
                img = cv2.imread(img_path, cv2.IMREAD_GRAYSCALE)
                img = cv2.resize(img, (48, 48))  # Redimensionar si es necesario
                images.append(img.flatten())  # Aplanar la imagen
                labels.append(i)

        return np.array(images), np.array(labels)

    def preprocess_data(self, X):
        """Normaliza los datos de píxeles"""
        return X / 255.0

    def train(self, save_model=True):
        """Entrena el modelo y opcionalmente lo guarda"""
        X, y = self.load_images("train")
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
            joblib.dump(self.model, "model/emotion_classifier.joblib")
            print("\nModelo guardado en model/emotion_classifier.joblib")

        return self.model