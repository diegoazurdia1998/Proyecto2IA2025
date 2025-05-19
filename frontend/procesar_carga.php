<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["modelo"])) {
    $nombre = basename($_FILES["modelo"]["name"]);
    $destino = "../models/" . $nombre;
    if (move_uploaded_file($_FILES["modelo"]["tmp_name"], $destino)) {
        echo "<h2>Modelo cargado exitosamente.</h2>";
        echo "<p>Guardado como: $nombre</p>";

        echo "<br><form method='get' action='ver_graficas.php'>";
        echo "<input type='hidden' name='modelo' value='" . htmlspecialchars($nombre) . "'>";
        echo "<button type='submit'>📊 Ver gráficas de este modelo</button>";
        echo "</form>";

    } else {
        echo "<h2>Error al guardar el archivo</h2>";
    }
    echo '<br><a href="index.php">← Volver al menú</a>';
} else {
    echo "<h2>Acceso no permitido</h2>";
}
?>
