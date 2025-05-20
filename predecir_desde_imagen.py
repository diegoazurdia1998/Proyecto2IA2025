import sys
import cv2
import json
from predictor import EmotionPredictor

# Validar argumentos
if len(sys.argv) != 3:
    print(json.dumps({"error": "Uso: python predecir_desde_imagen.py <modelo.keras> <imagen.jpg>"}))
    sys.exit(1)

modelo_path = sys.argv[1]
imagen_path = sys.argv[2]

# Cargar imagen
image = cv2.imread(imagen_path)
if image is None:
    print(json.dumps({"error": "No se pudo cargar la imagen"}))
    sys.exit(1)

# Predictor
predictor = EmotionPredictor()
predictor.load_model(modelo_path)
preds = predictor.predict(image)[0]

idx = preds.argmax()
prob = preds[idx] * 100
emocion = predictor.class_names[idx]

# Salida como JSON puro
print(json.dumps({
    "emocion": emocion,
    "probabilidad": round(prob, 2)
}))
