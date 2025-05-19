import sys
import cv2
from predictor import EmotionPredictor

if len(sys.argv) != 3:
    print("Uso: python predecir_desde_imagen.py <modelo.keras> <imagen.jpg>")
    sys.exit(1)

modelo_path = sys.argv[1]
imagen_path = sys.argv[2]

# Cargar imagen
image = cv2.imread(imagen_path)
if image is None:
    print("❌ Error: No se pudo cargar la imagen.")
    sys.exit(1)

# Predictor
predictor = EmotionPredictor()
predictor.load_model(modelo_path)
preds = predictor.predict(image)[0]
idx = preds.argmax()
prob = preds[idx] * 100

print(f"{predictor.class_names[idx]}: {prob:.2f}%")
