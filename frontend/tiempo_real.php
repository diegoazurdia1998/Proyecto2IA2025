<?php
$modelos = array_filter(glob("../models/*.keras"), 'is_file');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reconocimiento de Emociones en Tiempo Real</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('https://wallpapers.com/images/hd/4k-pc-sunset-art-h4ojcool3jx7ti67.jpg');
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
      border-radius: 15px;
      max-width: 800px;
      margin: 80px auto;
    }

    h2 {
      margin-bottom: 20px;
    }

    label {
      font-size: 18px;
    }

    select {
      font-size: 16px;
      padding: 10px;
      border-radius: 8px;
      border: none;
      margin-top: 10px;
      width: 60%;
      max-width: 300px;
    }

    input[type="hidden"] {
      display: none;
    }

    a {
      display: inline-block;
      margin-top: 30px;
      color: #ffffff;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Reconocimiento de Emociones en Tiempo Real</h2>

    <label for="modelo-dropdown">Selecciona un modelo:</label><br>
    <select id="modelo-dropdown">
      <?php foreach ($modelos as $archivo): ?>
        <?php $nombre = basename($archivo); ?>
        <option value="<?= $nombre ?>"><?= $nombre ?></option>
      <?php endforeach; ?>
    </select>

    <!-- input oculto para JS -->
    <input type="hidden" id="modelo-seleccionado" value="">

    <?php include("camara_predict.html"); ?>

    <a href="index.php">← Volver al menú principal</a>
  </div>

  <script>
    const dropdown = document.getElementById("modelo-dropdown");
    const modeloInput = document.getElementById("modelo-seleccionado");

    dropdown.addEventListener("change", () => {
      modeloInput.value = dropdown.value;
    });

    window.onload = () => {
      modeloInput.value = dropdown.value;
    };
  </script>

</body>
</html>
