# entrenar_modelo.py
import sys
from trainer import EmotionTrainer
from predictor import EmotionPredictor
import os

if len(sys.argv) != 3:
    print("Uso: python entrenar_modelo.py <epocas> <version>")
    sys.exit(1)

epocas = int(sys.argv[1])
version = sys.argv[2]

trainer = EmotionTrainer()
trainer.train(None, None, None, None, epochs=epocas)

base_path = os.path.dirname(os.path.abspath(__file__))
ruta_modelo = os.path.join(base_path, "models", f"emotion_model_{version}.keras")
trainer.save_model(ruta_modelo)

print(f"\nModelo entrenado correctamente: {ruta_modelo}")
