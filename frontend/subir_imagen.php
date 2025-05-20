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
            margin: 0;
            padding: 0;
            background-image: url('https://t4.ftcdn.net/jpg/07/15/25/97/360_F_715259753_THrzo4CG38J3JqmrLotxy7HE7VDe5PNY.jpg');
            background-size: cover;
            background-position: center;
            text-align: center;
            color: white;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 0 15px rgba(255,255,255,0.2);
            max-width: 500px;
            margin: 100px auto;
        }

        h2 {
            margin-bottom: 25px;
        }

        input[type="file"], select {
            margin: 15px 0;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            width: 90%;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background-color: #ffffff;
            color: #000000;
            cursor: pointer;
            margin-top: 15px;
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

        p {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Predicción de Emociones desde Imagen</h2>

    <?php if (!$hay_modelos): ?>
        <p style="color: #ff8080;"><strong>No hay modelos cargados.</strong></p>
        <p>Debes entrenar o cargar un modelo antes de predecir.</p>
        <a href="entrenar.php">🧠 Entrenar modelo</a><br>
        <a href="cargar_modelo.php">📁 Cargar modelo existente</a>
    <?php else: ?>
        <form action="procesar_imagen.php" method="post" enctype="multipart/form-data">
            <p><strong>Modelo cargado:</strong></p>
            <select name="modelo" required>
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
