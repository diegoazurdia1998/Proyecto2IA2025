<?php
header('Content-Type: application/json');
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Leer el cuerpo JSON recibido
$data = json_decode(file_get_contents("php://input"), true);

// Registrar lo que llega (debug)
file_put_contents("debug_log.txt", json_encode([
    "imagen_prefix" => substr($data["imagen"] ?? '', 0, 100),
    "modelo" => $data["modelo"] ?? null
], JSON_PRETTY_PRINT));

// Verificar si hay imagen y modelo
if (!isset($data['imagen']) || !isset($data['modelo'])) {
    echo json_encode(["error" => "Faltan datos (imagen o modelo)"]);
    exit;
}

// Procesar la imagen
$imagenBase64 = str_replace('data:image/jpeg;base64,', '', $data['imagen']);
$imagenBinaria = base64_decode($imagenBase64);
$nombreTemporal = "imagen_webcam.jpg";
file_put_contents($nombreTemporal, $imagenBinaria);

// Verificar que la imagen se haya guardado bien
if (!file_exists($nombreTemporal) || filesize($nombreTemporal) < 1000) {
    echo json_encode([
        "error" => "La imagen no se guardó correctamente.",
        "path" => realpath($nombreTemporal),
        "size" => filesize($nombreTemporal)
    ]);
    exit;
}

// Preparar rutas absolutas
$modelo = basename($data['modelo']); // Sanitizar
$modeloPath = realpath("../models/$modelo");
$imagenPath = realpath($nombreTemporal);
$scriptPath = realpath("../predecir_emocion_tiempo_real.py");
$pythonPath = realpath("../venv/Scripts/python.exe");

// Verificar rutas
if (!$modeloPath || !$imagenPath || !$scriptPath || !$pythonPath) {
    echo json_encode([
        "error" => "No se resolvieron correctamente las rutas",
        "modeloPath" => $modeloPath,
        "imagenPath" => $imagenPath,
        "scriptPath" => $scriptPath,
        "pythonPath" => $pythonPath
    ]);
    exit;
}

// Ejecutar el script
$comando = "\"$pythonPath\" \"$scriptPath\" \"$modeloPath\" \"$imagenPath\"";
$salida = shell_exec($comando);

// Registrar salida para debug
file_put_contents("debug_salida.txt", $salida);

// Validar salida
if (!$salida) {
    echo json_encode(["error" => "El script no devolvió salida"]);
    exit;
}

// Intentar decodificar como JSON
$resultado = json_decode($salida, true);

if ($resultado && isset($resultado['emocion']) && isset($resultado['probabilidad'])) {
    echo json_encode([
        "emocion" => $resultado['emocion'],
        "probabilidad" => $resultado['probabilidad']
    ]);
} else {
    echo json_encode([
        "error" => "Salida no válida del script",
        "raw" => $salida
    ]);
}
