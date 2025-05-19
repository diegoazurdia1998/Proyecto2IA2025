import os
import sys
import numpy as np
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from sklearn.metrics import classification_report, confusion_matrix

# Validar argumentos
if len(sys.argv) != 2:
    print("Uso: python graficar_resultado.py <modelo.keras>")
    sys.exit(1)

modelo_path = sys.argv[1]

# Directorios
base_dir = os.path.dirname(os.path.abspath(__file__))
test_dir = os.path.join(base_dir, "archive", "test")
graficas_dir = os.path.join(base_dir, "frontend", "graficas")
os.makedirs(graficas_dir, exist_ok=True)

# Clases
class_names = ['angry', 'disgust', 'fear', 'happy', 'neutral', 'sad', 'surprise']

# Cargar datos
test_datagen = ImageDataGenerator(rescale=1. / 255)
test_data = test_datagen.flow_from_directory(
    test_dir,
    target_size=(48, 48),
    color_mode='grayscale',
    class_mode='categorical',
    shuffle=False
)

# Cargar modelo
model = load_model(modelo_path)

# Predicción
predictions = model.predict(test_data)
y_pred = np.argmax(predictions, axis=1)
y_true = test_data.classes

# Reporte de métricas
report = classification_report(y_true, y_pred, target_names=class_names, output_dict=True)
df = pd.DataFrame(report).transpose()

# Guardar reporte CSV
csv_path = os.path.join(graficas_dir, "reporte.csv")
df.to_csv(csv_path, index=True)

# -------------------------------
# Matriz de Confusión (primero)
plt.figure(figsize=(10, 7))
conf_matrix = confusion_matrix(y_true, y_pred)
sns.heatmap(conf_matrix, annot=True, xticklabels=class_names, yticklabels=class_names, fmt='d', cmap='Blues')
plt.title('Matriz de Confusión')
plt.xlabel('Predicción')
plt.ylabel('Etiqueta real')
plt.tight_layout()
plt.savefig(os.path.join(graficas_dir, "confusion.png"))
plt.close()  # ✅ Importante cerrar

# -------------------------------
# Gráfico de barras de confianza
avg_probs = predictions.mean(axis=0)
plt.figure(figsize=(8, 4))
sns.barplot(x=class_names, y=avg_probs)
plt.title("Distribución de Confianza Promedio por Clase")
plt.ylabel("Probabilidad promedio")
plt.xlabel("Emoción")
plt.tight_layout()
plt.savefig(os.path.join(graficas_dir, "barras.png"))
plt.close()  # ✅ Importante cerrar

# -------------------------------
# Confirmación para PHP
print("OK")
