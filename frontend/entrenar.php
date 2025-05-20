<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrenar Modelo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://img.freepik.com/vector-gratis/noche-oceano-paisaje-luna-llena-estrellas-brillan_107791-7397.jpg?semt=ais_hybrid&w=740');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            text-align: center;
            color: white;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            margin: 100px auto;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
        }

        h1 {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-size: 18px;
        }

        input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background-color: #ffffff;
            color: #000000;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #dddddd;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Entrenamiento del Modelo de Emociones</h1>

        <form action="procesar_entrenamiento.php" method="post">
            <label for="epocas">Número de épocas:</label>
            <input type="number" id="epocas" name="epocas" min="1" required>

            <label for="version">Versión del modelo:</label>
            <input type="text" id="version" name="version" placeholder="ej. v1, prueba, final" required>

            <button type="submit">Entrenar Modelo</button>
        </form>

        <a href="index.php">← Volver al menú principal</a>
    </div>

</body>
</html>
