from keras import Sequential
from keras.api.layers import Conv2D, MaxPooling2D, Flatten, Dense, Dropout
from keras.api.callbacks import TensorBoard, ModelCheckpoint
from keras.src.legacy.preprocessing.image import ImageDataGenerator
import datetime
import matplotlib.pyplot as plt
import numpy as np


class EmotionTrainer:
    def __init__(self, train_dir="archive/train", test_dir="archive/test", target_size=(48, 48), batch_size=32):
        """
        Inicializa el entrenador con las rutas de los datos.

        :param train_dir: Ruta de la carpeta de entrenamiento.
        :param test_dir: Ruta de la carpeta de prueba.
        :param target_size: Tamaño al que se redimensionarán las imágenes (48x48 para FER2013).
        :param batch_size: Tamaño del lote para el generador de datos.
        """
        self.train_dir = train_dir
        self.test_dir = test_dir
        self.target_size = target_size
        self.batch_size = batch_size
        self.model = self._build_model()
        self.class_names = ['angry', 'disgust', 'fear', 'happy', 'neutral', 'sad', 'surprise']  # Orden alfabético

    def _build_model(self):
        """Construye la arquitectura CNN."""
        model = Sequential([
            # Capa convolucional 1
            Conv2D(32, (3, 3), activation='relu', input_shape=(self.target_size[0], self.target_size[1], 1)),
            MaxPooling2D((2, 2)),

            # Capa convolucional 2
            Conv2D(64, (3, 3), activation='relu'),
            MaxPooling2D((2, 2)),

            # Capa convolucional 3 (opcional para mejorar precisión)
            Conv2D(128, (3, 3), activation='relu'),
            MaxPooling2D((2, 2)),

            # Aplanar y capas densas
            Flatten(),
            Dense(128, activation='relu'),
            Dropout(0.5),  # Regularización para evitar overfitting
            Dense(7, activation='softmax')  # 7 clases de emociones
        ])

        model.compile(
            optimizer='adam',
            loss='categorical_crossentropy',
            metrics=['accuracy']
        )
        return model

    def _create_data_generators(self):
        """Crea generadores de datos con aumento de datos para entrenamiento y validación."""
        train_datagen = ImageDataGenerator(
            rescale=1. / 255,  # Normalización
            rotation_range=10,  # Pequeñas rotaciones para aumento de datos
            zoom_range=0.1,
            horizontal_flip=True  # Útil para rostros
        )

        test_datagen = ImageDataGenerator(rescale=1. / 255)  # Solo normalización para test

        train_generator = train_datagen.flow_from_directory(
            self.train_dir,
            target_size=self.target_size,
            color_mode='grayscale',
            batch_size=self.batch_size,
            class_mode='categorical'
        )

        test_generator = test_datagen.flow_from_directory(
            self.test_dir,
            target_size=self.target_size,
            color_mode='grayscale',
            batch_size=self.batch_size,
            class_mode='categorical'
        )

        return train_generator, test_generator
    def train(self, X_train, y_train, X_val, y_val, epochs=10, batch_size=32):
        """Entrena el modelo con visualización del progreso."""
        train_generator, test_generator = self._create_data_generators()
        log_dir = "logs/fit/" + datetime.datetime.now().strftime("%Y%m%d-%H%M%S")
        callbacks = [
            TensorBoard(log_dir=log_dir, histogram_freq=1),
            ModelCheckpoint(filepath='models/emotion_model.keras', save_best_only=True)
        ]
        history = self.model.fit(
            train_generator,
            validation_data=test_generator,
            epochs=epochs,
            callbacks=callbacks
        )

        # Mostrar ejemplo de predicción después del entrenamiento
        self.display_prediction_example(test_generator)
        return history

    def save_model(self, path='models/emotion_model.keras'):
        """Guarda el modelo entrenado."""
        self.model.save(path)

    def display_prediction_example(self, test_generator):
        """Muestra las probabilidades de predicción para un lote de imágenes de prueba."""

        # Obtener un lote de datos de prueba
        test_images, test_labels = next(test_generator)

        # Predecir emociones para el lote
        predictions = self.model.predict(test_images)

        # Seleccionar una imagen aleatoria del lote
        idx = np.random.randint(0, len(test_images))
        image = test_images[idx]
        true_label = np.argmax(test_labels[idx])
        pred_probs = predictions[idx]

        # Mostrar imagen y probabilidades
        plt.imshow(image.squeeze(), cmap='gray')
        plt.title(f"True: {self.class_names[true_label]}")
        plt.axis('off')
        plt.show()

        # Imprimir probabilidades en consola
        print("\nProbabilidades por emoción:")
        for i, (class_name, prob) in enumerate(zip(self.class_names, pred_probs)):
            print(f"{class_name}: {prob * 100:.2f}%")