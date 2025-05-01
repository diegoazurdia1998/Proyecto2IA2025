import cv2
import numpy as np
from predictor import EmotionPredictor
from trainer import EmotionTrainer


def main():
    print("""
    ====================================
    SISTEMA DE RECONOCIMIENTO DE EMOCIONES
    ====================================
    """)

    while True:
        print("\nOpciones:")
        print("1. Entrenar modelo")
        print("2. Probar con imagen de archivo")
        print("3. Usar cámara web")
        print("4. Salir")

        choice = input("Seleccione una opción: ")

        if choice == "1":
            print("\nIniciando entrenamiento...")
            trainer = EmotionTrainer()
            trainer.train()


        elif choice == "2":

            try:

                predictor = EmotionPredictor()

                img_path = input("Ingrese la ruta de la imagen: ")

                image = cv2.imread(img_path)

                if image is None:
                    print("Error: No se pudo cargar la imagen")

                    continue

                print("\nAnalizando imagen...")

                result = predictor.predict_emotion(image)  # Esto ahora mostrará las probabilidades

                # Mostrar resultado principal

                print(
                    f"\n➤ Emoción predominante: {result['emotion'].upper()} ({result['confidence']:.2%} de confianza)")


            except Exception as e:

                print(f"Error: {str(e)}")


        elif choice == "3":

            try:

                predictor = EmotionPredictor()

                cap = cv2.VideoCapture(0)

                print("\nPresione 'q' para salir de la cámara...")

                while True:

                    ret, frame = cap.read()

                    if not ret:
                        break

                    # Predecir (sin mostrar probabilidades en consola para no saturar)

                    result = predictor.predict_emotion(frame, show_probs=False)

                    # Mostrar emoción principal

                    emotion_text = f"{result['emotion']} ({result['confidence']:.0%})"

                    cv2.putText(frame, emotion_text, (10, 30),

                                cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

                    # Mostrar todas las probabilidades en la imagen

                    y_offset = 70

                    for emo, prob in sorted(result["all_probas"].items(),

                                            key=lambda x: x[1], reverse=True):
                        prob_text = f"{emo}: {prob:.0%}"

                        cv2.putText(frame, prob_text, (10, y_offset),

                                    cv2.FONT_HERSHEY_SIMPLEX, 0.7,

                                    (0, 255, 0) if emo == result["emotion"] else (200, 200, 200),

                                    1)

                        y_offset += 30

                    cv2.imshow('Reconocimiento de Emociones', frame)

                    if cv2.waitKey(1) & 0xFF == ord('q'):
                        break

                cap.release()

                cv2.destroyAllWindows()

            except Exception as e:
                print(f"Error: {str(e)}")

        elif choice == "4":
            print("Saliendo del programa...")
            break

        else:
            print("Opción no válida. Intente nuevamente.")


if __name__ == "__main__":
    main()