<?php
$modelos = glob("../models/*.keras"); // busca modelos
$hay_modelos = count($modelos) > 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir imagen para predicción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f5f5f5;
            text-align: center;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            max-width: 500px;
        }
        input[type="file"], select {
            margin: 10px 0;
        }
        a, button {
            display: inline-block;
            margin-top: 10px;
            color: #5e2b88;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Predicción de Emociones desde Imagen</h2>

    <?php if (!$hay_modelos): ?>
        <p style="color: red;"><strong>No hay modelos cargados.</strong></p>
        <p>Debes entrenar o cargar un modelo antes de predecir.</p>
        <a href="entrenar.php">🧠 Entrenar modelo</a><br>
        <a href="cargar_modelo.php">📁 Cargar modelo existente</a>
    <?php else: ?>
        <form action="procesar_imagen.php" method="post" enctype="multipart/form-data">
            <p><strong>Modelo cargado:</strong></p>
            <select name="modelo">
                <?php foreach ($modelos as $modelo): ?>
                    <option value="<?= basename($modelo) ?>">
                        <?= basename($modelo) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <p><strong>Selecciona una imagen:</strong></p>
            <input type="file" name="imagen" accept=".jpg,.jpeg,.png" required><br>

            <button type="submit">Predecir emoción</button>
        </form>

        <a href="index.php">← Volver al menú</a>
    <?php endif; ?>
</div>

</body>
</html>
