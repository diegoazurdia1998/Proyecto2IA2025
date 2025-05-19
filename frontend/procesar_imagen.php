<?php
// Soporte para UTF-8
header('Content-Type: text/html; charset=utf-8');

// Tiempo máximo ampliado
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de la Predicción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 40px;
        }
        .resultado {
            background: white;
            padding: 25px;
            margin: 20px auto;
            border-radius: 10px;
            max-width: 700px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: left;
            font-family: Consolas, monospace;
        }
        .spinner {
            border: 8px solid #eee;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .hidden { display: none; }
        a {
            color: purple;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>Resultado de la Predicción</h2>
<div id="spinner" class="spinner"></div>
<p id="cargando">Procesando imagen, esto puede tardar unos segundos...</p>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $modelo = $_POST["modelo"];
    $imagen = $_FILES["imagen"];

    echo '<div id="resultado" class="resultado hidden">';
    echo "<strong>Modelo usado:</strong> $modelo<br><br>";

    if ($imagen["error"] === 0) {
        $ruta_imagen = "imagen_temporal.jpg";

        if (move_uploaded_file($imagen["tmp_name"], $ruta_imagen)) {
            // Rutas absolutas con seguridad
            $pythonPath = realpath("../venv/Scripts/python.exe");
            $scriptPath = realpath("../predecir_emocion.py");
            $modeloPath = realpath(__DIR__ . "/../models/$modelo");
            $rutaAbsolutaImagen = realpath($ruta_imagen);

            if (!$pythonPath || !$scriptPath || !$modeloPath || !$rutaAbsolutaImagen) {
                echo "<p><strong>Error:</strong> No se pudo resolver una de las rutas necesarias.</p>";
            } else {
                // Comando final con rutas entre comillas
                $comando = "\"$pythonPath\" \"$scriptPath\" \"$modeloPath\" \"$rutaAbsolutaImagen\"";

                // Ejecutar
                $salida = shell_exec($comando);

                if (!empty($salida)) {
                    echo "<pre>" . htmlspecialchars($salida, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";
                } else {
                    echo "<p><strong>Error:</strong> No se recibió salida.</p>";
                }
            }

        } else {
            echo "<p><strong>Error:</strong> Fallo al mover imagen desde temporal.</p>";
        }

    } else {
        echo "<p><strong>Error:</strong> No se recibió imagen correctamente.</p>";
    }

    echo "<br><a href='index.php'>← Volver al menú</a>";
    echo '</div>';
}
?>

<script>
    // Mostrar el resultado y ocultar la animación al terminar carga
    window.onload = () => {
        document.getElementById("spinner").classList.add("hidden");
        document.getElementById("cargando").classList.add("hidden");
        document.getElementById("resultado").classList.remove("hidden");
    };
</script>

</body>
</html>
