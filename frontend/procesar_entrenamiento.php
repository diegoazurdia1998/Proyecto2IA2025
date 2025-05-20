<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrenando Modelo</title>
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
            margin: 80px auto;
            border-radius: 15px;
            width: 90%;
            max-width: 800px;
        }

        .spinner {
            border: 8px solid #eee;
            border-top: 8px solid #00bfff;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        pre {
            text-align: left;
            background-color: #ffffff;
            color: #000000;
            padding: 20px;
            border-radius: 10px;
            max-width: 100%;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.4);
            overflow-x: auto;
            white-space: pre-wrap;
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
            color: #ffffff;
            text-decoration: underline;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container" id="cargando">
    <h2>Entrenando el modelo...</h2>
    <div class="spinner"></div>
    <p>Esto puede tardar varios minutos. Por favor, espera.</p>
</div>

<?php
set_time_limit(900); // 15 minutos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $epocas = intval($_POST["epocas"]);
    $version = escapeshellarg($_POST["version"]);

    $pythonPath = "..\\venv\\Scripts\\python.exe";
    $comando = "$pythonPath ../entrenar_modelo.py $epocas $version 2>&1";

    $salida = shell_exec($comando);
    $salida_limpia = preg_replace('/\x1b\[[0-9;]*m/', '', $salida);

    echo "<script>document.getElementById('cargando').style.display = 'none';</script>";
    echo "<div class='container'>";
    echo "<h2>Resultado del entrenamiento</h2>";

    if (!empty($salida_limpia)) {
        preg_match('/\d+\/\d+.*accuracy:.*val_loss:.*$/m', $salida_limpia, $resumen);
        preg_match('/Probabilidades por .*?:\s*(.*?)Modelo entrenado correctamente:/s', $salida_limpia, $probabilidades);
        preg_match('/Modelo entrenado correctamente: .*/', $salida_limpia, $ruta);

        $bloque_final = trim(
            ($resumen[0] ?? '') . "\n\n" .
            "Probabilidades por emoción:\n" . ($probabilidades[1] ?? '') . "\n\n" .
            ($ruta[0] ?? '')
        );

        echo "<pre>" . htmlspecialchars($bloque_final, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";

        if (!empty($salida_limpia)) {
            $version_raw = $_POST["version"];
            $version_sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '', $version_raw);
            echo "<form method='get' action='ver_graficas.php'>";
            echo "<input type='hidden' name='modelo' value='emotion_model_{$version_sanitized}.keras'>";
            echo "<button type='submit'>📊 Ver gráficas de este modelo</button>";
            echo "</form>";
        }
    } else {
        echo "<p><strong>Error:</strong> No se recibió salida del script Python.</p>";
    }

    echo '<br><a href="index.php">← Volver al menú</a>';
    echo "</div>";
} else {
    echo "<div class='container'><p><strong>Acceso no permitido.</strong></p></div>";
}
?>

</body>
</html>
