Proceso de instalación:
Descripción: El proyecto es un detector de emociones, las cuales son clasificadas dentro de 7 distintas categorías, siendo estas angry, happy, sad, neutral, fearful, disgust y surprise. En donde por medio de una página web html se puede elegir entre sí leer una imagen o hacerlo en tiempo real.
Requerimientos:
- Python 3.13 o superior
- PHP
Librerías:
tensorflow>=2.8
numpy
opencv-python
Pillow
matplotlib
scikit-learn
Ejecución:
Se ingresa a la página web y se elige la opción que el usuario desee ejecutar dentro de la lista de opciones.
Uso de página web:
Se da a elegir entre las siguientes opciones:
- Entrenar modelo: Entrena un nuevo modelo desde cero con la cantidad de etapas que se deseen.
- Cargar modelo existente: Hace que se utilice un modelo anteriormente creado para las predicciones.
- Ver gráficas: Muestra gráficas estadísticas al igual que una matriz de confusión.
- Predecir desde imagen: El usuario sube una imagen a ser evaluada.
- Predicción tiempo real: Se enciende la cámara disponible del dispositivo y se evalúa la emoción al conforme avanzan los fotogramas.
- Observaciones: Debido al dataset y el tamaño de muestras de ciertas emociones, los resultados pueden verse parcializados hacia un tipo de emoción.
