import cv2
import numpy as np
from sklearn.base import BaseEstimator, TransformerMixin

class ImagePreprocessor:
    def __init__(self, target_size=(48, 48)):
        self.target_size = target_size

    def resize(self, image):
        """Redimensiona la imagen al tamaño objetivo."""
        return cv2.resize(image, self.target_size)

    def normalize(self, image):
        """Normaliza los píxeles al rango [0, 1]."""
        return image / 255.0

    def grayscale(self, image):
        """Convierte la imagen a escala de grises (si no lo está)."""
        if len(image.shape) > 2:
            return cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        return image

    def preprocess(self, image):
        """Pipeline completo de preprocesamiento."""
        image = self.grayscale(image)
        image = self.resize(image)
        image = self.normalize(image)
        return np.expand_dims(image, axis=-1)  # Añade dimensión del canal (48, 48, 1)