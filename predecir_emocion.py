import sys
import io
import cv2
from predictor import EmotionPredictor

# Forzar salida en UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def main():
    if len(sys.argv) != 3:
        print("Uso: python predecir_emocion.py <modelo.keras> <imagen.jpg>")
        sys.exit(1)

    modelo_path = sys.argv[1]
    imagen_path = sys.argv[2]

    # Cargar imagen
    image = cv2.imread(imagen_path)
    if image is None:
        print(f"❌ Error: No se pudo cargar la imagen '{imagen_path}'")
        sys.exit(1)

    # Instanciar el predictor y cargar el modelo
    predictor = EmotionPredictor()
    predictor.load_model(modelo_path)

    # Obtener resultado formateado
    resultado = predictor.display_prediction(image)

    # Imprimir el resultado (¡muy importante!)
    print(resultado)

if __name__ == "__main__":
    main()
