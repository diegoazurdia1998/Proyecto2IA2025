<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Verifica si hay imagen y modelo
if (!isset($data['imagen']) || !isset($data['modelo'])) {
    echo json_encode(["error" => "Faltan datos (imagen o modelo)"]);
    exit;
}

// Decodificar imagen base64
$imagenBase64 = $data['imagen'];
$imagenBase64 = str_replace('data:image/jpeg;base64,', '', $imagenBase64);
$imagenBinaria = base64_decode($imagenBase64);

// Guardar temporalmente como imagen
$rutaTemporal = 'imagen_webcam.jpg';
file_put_contents($rutaTemporal, $imagenBinaria);

// Ejecutar Python
$modelo = escapeshellarg("models/" . $data['modelo']);
$comando = "..\\venv\\Scripts\\python.exe predecir_desde_imagen.py $modelo $rutaTemporal";
$salida = shell_exec($comando);

// Extraer resultado JSON
if ($salida) {
    $resultado = json_decode($salida, true);
    if ($resultado && isset($resultado['emocion'])) {
        echo json_encode($resultado);
    } else {
        echo json_encode(["error" => "Salida no válida del script", "raw" => $salida]);
    }
} else {
    echo json_encode(["error" => "No hubo salida del script"]);
}
?>
