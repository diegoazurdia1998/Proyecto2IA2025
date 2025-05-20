<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar Modelo Existente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://image.slidesdocs.com/responsive-images/background/vibrant-red-galaxy-and-stunning-starry-backdrop-a-horizontal-banner-depicting-the-wonders-of-space-powerpoint-background_4a98088522__960_540.jpg');
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

        input[type="file"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            margin-top: 10px;
            background-color: white;
            color: black;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background-color: #ffffff;
            color: #000000;
            cursor: pointer;
            margin-top: 20px;
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
        <h1>Cargar Modelo Existente</h1>

        <form action="procesar_carga.php" method="post" enctype="multipart/form-data">
            <label for="modelo">Selecciona el archivo del modelo (.h5 o .keras):</label><br>
            <input type="file" name="modelo" id="modelo" accept=".h5,.keras" required>

            <br>
            <button type="submit">Cargar modelo</button>
        </form>

        <a href="index.php">← Volver al menú principal</a>
    </div>

</body>
</html>
