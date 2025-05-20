import sys
import io
import cv2
import json
from predictor import EmotionPredictor

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def main():
    if len(sys.argv) != 3:
        print(json.dumps({"error": "Uso incorrecto. Se esperaban modelo e imagen"}))
        sys.exit(1)

    modelo_path = sys.argv[1]
    imagen_path = sys.argv[2]

    # Cargar imagen
    image = cv2.imread(imagen_path)
    if image is None:
        print(json.dumps({"error": f"No se pudo cargar la imagen {imagen_path}"}))
        sys.exit(1)

    # Inicializar predictor
    predictor = EmotionPredictor()
    predictor.load_model(modelo_path)

    # Obtener predicción en crudo
    preds = predictor.predict(image)[0]
    idx = preds.argmax()
    emocion = predictor.class_names[idx]
    probabilidad = round(float(preds[idx]) * 100, 2)

    print(json.dumps({
        "emocion": emocion,
        "probabilidad": probabilidad
    }))

if __name__ == "__main__":
    main()
