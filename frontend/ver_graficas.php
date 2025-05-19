<?php
header('Content-Type: text/html; charset=utf-8');
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualización de Resultados de Entrenamiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 40px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            margin: 10px;
        }
        h2 {
            color: #222;
        }
        .tabla-resultados {
            margin: 20px auto;
            border-collapse: collapse;
        }
        .tabla-resultados th, .tabla-resultados td {
            border: 1px solid #ccc;
            padding: 8px 15px;
        }
        .tabla-resultados th {
            background-color: #eee;
        }
        img {
            max-width: 100%;
            margin: 20px 0;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .section-title {
            margin-top: 30px;
            font-size: 20px;
        }
    </style>
</head>
<body>

<h2>Visualización de Resultados de Entrenamiento</h2>

<form method="POST">
    <label for="modelo">Selecciona un modelo:</label>
    <select name="modelo" id="modelo" required>
        <?php
        $modelos = array_filter(scandir(__DIR__ . '/../models'), function($file) {
            return preg_match('/\.keras$/', $file);
        });
        foreach ($modelos as $m) {
            $selected = (isset($_POST['modelo']) && $_POST['modelo'] === $m) ? 'selected' : '';
            echo "<option value=\"$m\" $selected>$m</option>";
        }
        ?>
    </select>
    <button type="submit">Ver métricas</button>
</form>

<?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
    <h3>Resultados:</h3>

    <?php
    $modelo = $_POST["modelo"];
    $python = realpath(__DIR__ . "/../venv/Scripts/python.exe");
    $script = realpath(__DIR__ . "/../graficar_resultado.py");
    $modelo_path = realpath(__DIR__ . "/../models/$modelo");

    if (!$python || !$script || !$modelo_path) {
        echo "<p class='error'>Error: No se pudieron resolver las rutas correctamente.</p>";
    } else {
        $comando = "\"$python\" \"$script\" \"$modelo_path\"";
        $salida = shell_exec($comando);

        if (strpos($salida, "OK") === false) {
            echo "<pre><strong>Comando ejecutado:</strong>\n$comando</pre>";
            echo "<pre><strong>Salida:</strong>\n" . ($salida ?: "null") . "</pre>";
            echo "<p class='error'>Error: No se generaron los resultados correctamente.</p>";
        } else {
            // Mostrar la gráfica de barras
            echo "<div class='section-title'>Gráfico de Métricas por Clase:</div>";
            echo "<img src='graficas/barras.png' alt='Gráfico de barras'>";

            // Mostrar la matriz de confusión
            echo "<div class='section-title'>Matriz de Confusión:</div>";
            echo "<img src='graficas/confusion.png' alt='Matriz de confusión'>";

            // Leer CSV (reporte)
            $csv = __DIR__ . "/graficas/reporte.csv";
            if (file_exists($csv)) {
                echo "<div class='section-title'>Métricas:</div>";
                echo "<table class='tabla-resultados'>";
                $handle = fopen($csv, "r");
                $headers = fgetcsv($handle, 1000, ",", '"', "\\");
                echo "<tr>";
                foreach ($headers as $col) echo "<th>$col</th>";
                echo "</tr>";
                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
                    echo "<tr>";
                    foreach ($row as $cell) echo "<td>" . htmlspecialchars($cell) . "</td>";
                    echo "</tr>";
                }
                fclose($handle);
                echo "</table>";
            } else {
                echo "<p class='error'>No se encontró el archivo de métricas.</p>";
            }
        }
    }
    ?>

    <br><a href="index.php">← Volver al menú</a>
<?php endif; ?>

</body>
</html>
