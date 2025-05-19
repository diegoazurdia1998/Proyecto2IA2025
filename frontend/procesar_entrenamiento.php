<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrenando Modelo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
            text-align: center;
        }
        .spinner {
            border: 8px solid #eee;
            border-top: 8px solid #3498db;
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
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 1200px;
            width: 90%;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-x: auto;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>

<div id="cargando">
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
    echo "<h2>Resultado del entrenamiento</h2>";

    if (!empty($salida_limpia)) {
        // Filtra la parte que contiene solo los resultados
        // Captura línea de resumen
        preg_match('/\d+\/\d+.*accuracy:.*val_loss:.*$/m', $salida_limpia, $resumen);

        // Captura probabilidades aunque haya errores de encoding
        preg_match('/Probabilidades por .*?:\s*(.*?)Modelo entrenado correctamente:/s', $salida_limpia, $probabilidades);

        // Captura el mensaje final
        preg_match('/Modelo entrenado correctamente: .*/', $salida_limpia, $ruta);

        // Construye el bloque final
        $bloque_final = trim(
            ($resumen[0] ?? '') . "\n\n" .
            "Probabilidades por emoción:\n" . ($probabilidades[1] ?? '') . "\n\n" .
            ($ruta[0] ?? '')
        );

        echo "<pre>" . htmlspecialchars($bloque_final, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";

        if (!empty($salida_limpia)) {
            // (bloque de extracción y muestra del resultado)

            echo "<br><form method='get' action='ver_graficas.php'>";
            $version_raw = $_POST["version"];
            $version_sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '', $version_raw);

            echo "<input type='hidden' name='modelo' value='emotion_model_{$version_sanitized}.keras'>";
            echo "<button type='submit'>📊 Ver gráficas de este modelo</button>";
            echo "</form>";
        }


    } else {
        echo "<p><strong>Error:</strong> No se recibió salida del script Python.</p>";
    }

    echo '<br><a href="index.php">← Volver al menú</a>';
} else {
    echo "Acceso no permitido.";
}
?>

</body>
</html>
