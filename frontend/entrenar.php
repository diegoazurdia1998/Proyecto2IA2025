<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrenar Modelo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            margin: 10px;
        }
    </style>
</head>
<body>

    <h1>Entrenamiento del Modelo de Emociones</h1>

    <form action="procesar_entrenamiento.php" method="post">
        <label for="epocas">Número de épocas:</label>
        <input type="number" id="epocas" name="epocas" min="1" required>

        <br>

        <label for="version">Versión del modelo:</label>
        <input type="text" id="version" name="version" placeholder="ej. v1, prueba, final" required>

        <br>

        <button type="submit">Entrenar Modelo</button>
    </form>

    <br>
    <a href="index.php">← Volver al menú principal</a>

</body>
</html>
