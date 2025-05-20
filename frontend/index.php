<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reconocimiento de Emociones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 0;
            margin: 0;
            background-image: url('https://img.freepik.com/vector-gratis/noche-oceano-paisaje-luna-llena-estrellas-brillan_107791-7397.jpg?semt=ais_hybrid&w=740');
            background-size: cover;
            background-position: center;
            height: 100vh;
            color: white;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            margin: 100px auto;
            border-radius: 15px;
            width: 300px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 24px;
        }

        .menu form {
            margin: 10px 0;
        }

        .menu button {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            background-color: #ffffff;
            color: #000000;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .menu button:hover {
            background-color: #dddddd;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Menú de Reconocimiento de Emociones</h1>

        <div class="menu">
            <form action="entrenar.php" method="get">
                <button type="submit">Entrenar modelo</button>
            </form>

            <form action="cargar_modelo.php" method="get">
                <button type="submit">Cargar modelo existente</button>
            </form>

            <form action="ver_graficas.php" method="get">
                <button type="submit">Ver gráficas</button>
            </form>
            
            <form action="subir_imagen.php" method="get">
                <button type="submit">Predecir desde imagen</button>
            </form>

            <form action="tiempo_real.php" method="get">
                <button type="submit">Predicción en tiempo real</button>
            </form>
        </div>
    </div>

</body>
</html>
