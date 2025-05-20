from trainer import EmotionTrainer
from predictor import EmotionPredictor
import cv2
import tkinter as tk
from tkinter import filedialog


class EmotionApp:
    def __init__(self):
        self.trainer = EmotionTrainer()  # Inicializa el entrenador
        self.predictor = EmotionPredictor()  # Inicializa el predictor
        self.root = tk.Tk()
        self.root.withdraw()  # Oculta la ventana principal de tkinter

    def display_menu(self):
        """Muestra el menú de opciones al usuario."""
        print("\n--- Menú de Reconocimiento de Emociones ---")
        print("1. Entrenar el modelo")
        print("2. Cambiar modelo para predicciones")
        print("3. Predecir emociones de una imagen")
        print("4. Predecir emociones en tiempo real con la cámara web")
        print("5. Salir")
        print("---------------------------------------------")

    def run(self):
        """Ejecuta el menú y maneja las opciones del usuario."""
        while True:
            self.display_menu()
            choice = input("Seleccione una opción (1-5): ")

            if choice == '1':
                self.train_model()
            elif choice == '2':
                self.change_model()
            elif choice == '3':
                self.predict_image()
            elif choice == '4':
                self.predict_webcam()
            elif choice == '5':
                print("Saliendo del programa...")
                break
            else:
                print("Opción no válida. Intente de nuevo.")

    def train_model(self):
        """Entrena el modelo y muestra el progreso."""
        epochs = int(input("Ingrese el número de épocas para el entrenamiento: "))
        history = self.trainer.train(None, None, None, None, epochs=epochs)

        # Guardar el modelo entrenado
        new_model_path = input("Ingrese la version: ")
        new_model_path = "models/emotion_model_" + new_model_path +".keras"
        self.trainer.save_model(new_model_path)

        # Asignar el modelo al predictor
        if self.predictor.model is None:
            self.predictor.load_model(new_model_path)  # Cargar el nuevo modelo
            print("Modelo entrenado y asignado al predictor.")
        else:
            change_model = input("Ya hay un modelo asignado. ¿Desea cambiarlo? (s/n): ")
            if change_model.lower() == 's':
                self.predictor.load_model(new_model_path)  # Cargar el nuevo modelo
                print("Modelo anterior guardado y nuevo modelo asignado al predictor.")
            else:
                print("El modelo no ha sido cambiado.")

    def change_model(self):
        """Cambia el modelo actual para las predicciones."""
        new_model_path = self.select_file("Seleccionar modelo", "Modelo (*.h5)")
        self.predictor.load_model(new_model_path)
        print("Modelo cambiado exitosamente.")

    def predict_image(self):
        """Predice emociones a partir de una imagen proporcionada por el usuario."""
        image_path = self.select_file("Seleccionar imagen", "Imagen (*.jpg;*.jpeg;*.png)")
        image = cv2.imread(image_path)
        if image is not None:
            self.predictor.display_prediction(image)
        else:
            print("No se pudo cargar la imagen. Verifique la ruta.")

    def predict_webcam(self):
        """Inicia la predicción en tiempo real utilizando la cámara web."""
        print("Iniciando la predicción en tiempo real. Presione 'q' para salir.")
        self.predictor.predict_from_webcam()

    def select_file(self, title, filetypes):
        """Abre un diálogo para seleccionar un archivo y devuelve la ruta del archivo seleccionado."""
        file_path = filedialog.askopenfilename(title=title, filetypes=[(filetypes, "*.h5"), ("All Files", "*.*")])
        return file_path if file_path else None


if __name__ == "__main__":
    app = EmotionApp()
    app.run()
