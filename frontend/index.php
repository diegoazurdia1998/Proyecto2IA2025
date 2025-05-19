<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reconocimiento de Emociones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            margin-bottom: 30px;
        }
        .menu button {
            margin: 10px;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

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

</body>
</html>
