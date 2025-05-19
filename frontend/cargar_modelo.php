<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar Modelo Existente</title>
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

    <h1>Cargar Modelo Existente</h1>

    <form action="procesar_carga.php" method="post" enctype="multipart/form-data">
        <label for="modelo">Selecciona el archivo del modelo (.h5 o .keras):</label><br>
        <input type="file" name="modelo" id="modelo" accept=".h5,.keras" required>

        <br><br>
        <button type="submit">Cargar modelo</button>
    </form>

    <br>
    <a href="index.php">← Volver al menú principal</a>

</body>
</html>
