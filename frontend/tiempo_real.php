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
      background-color: #f4f4f4;
      text-align: center;
      padding: 20px;
    }
    select {
      font-size: 16px;
      padding: 5px;
      margin: 10px auto;
    }
  </style>
</head>
<body>

<h2>Reconocimiento de Emociones en Tiempo Real</h2>

<label for="modelo-dropdown">Selecciona un modelo:</label>
<select id="modelo-dropdown">
  <?php foreach ($modelos as $archivo): ?>
    <?php $nombre = basename($archivo); ?>
    <option value="<?= $nombre ?>"><?= $nombre ?></option>
  <?php endforeach; ?>
</select>

<!-- input para JS -->
<input type="hidden" id="modelo-seleccionado" value="">

<?php include("camara_predict.html"); ?>

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
