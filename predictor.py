import cv2
import numpy as np
import joblib


class EmotionPredictor:
    def __init__(self, model_path="model/emotion_classifier.joblib"):
        self.emotions = ["angry", "disgust", "fear", "happy", "neutral", "sad", "surprise"]
        try:
            self.model = joblib.load(model_path)
            print(f"Modelo cargado desde {model_path}")
        except FileNotFoundError:
            raise ValueError("Modelo no encontrado. Por favor entrena el modelo primero.")

    def preprocess_image(self, image):
        """Preprocesa una imagen para la predicción"""
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        resized = cv2.resize(gray, (48, 48))
        return (resized.flatten() / 255.0).reshape(1, -1)

    def predict_emotion(self, image, show_probs=True):
        """Predice la emoción en una imagen y muestra probabilidades"""
        processed = self.preprocess_image(image)
        probas = self.model.predict_proba(processed)[0]
        emotion_idx = self.model.predict(processed)[0]

        result = {
            "emotion": self.emotions[emotion_idx],
            "confidence": probas[emotion_idx],
            "all_probas": {e: float(p) for e, p in zip(self.emotions, probas)}
        }

        if show_probs:
            print("\nProbabilidades de emociones:")
            for emotion, prob in result["all_probas"].items():
                print(f"{emotion}: {prob:.2%}")  # Formato de porcentaje con 2 decimales

        return result