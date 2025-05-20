<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Carga</title>
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
            max-width: 600px;
        }

        h2 {
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
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
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["modelo"])) {
    $nombre = basename($_FILES["modelo"]["name"]);
    $destino = "../models/" . $nombre;
    if (move_uploaded_file($_FILES["modelo"]["tmp_name"], $destino)) {
        echo "<h2>✅ Modelo cargado exitosamente.</h2>";
        echo "<p>Guardado como: <strong>$nombre</strong></p>";

        echo "<form method='get' action='ver_graficas.php'>";
        echo "<input type='hidden' name='modelo' value='" . htmlspecialchars($nombre) . "'>";
        echo "<button type='submit'>📊 Ver gráficas de este modelo</button>";
        echo "</form>";
    } else {
        echo "<h2>❌ Error al guardar el archivo</h2>";
    }
    echo '<a href="index.php">← Volver al menú</a>';
} else {
    echo "<h2>Acceso no permitido</h2>";
    echo '<a href="index.php">← Volver al menú</a>';
}
?>
</div>

</body>
</html>
