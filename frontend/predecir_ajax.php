<?php
header('Content-Type: application/json');
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Leer los datos recibidos del frontend (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Validar que lleguen los campos requeridos
if (!isset($data['imagen']) || !isset($data['modelo'])) {
    file_put_contents("resultado_actual.txt", "❌ Faltan datos (imagen o modelo)");
    echo json_encode(["error" => "Faltan datos (imagen o modelo)"]);
    exit;
}

// Procesar imagen base64
$imagenBase64 = str_replace('data:image/jpeg;base64,', '', $data['imagen']);
$imagenBinaria = base64_decode($imagenBase64);
$archivoImagen = "imagen_webcam.jpg";
file_put_contents($archivoImagen, $imagenBinaria);

// Verificar que se guardó correctamente
if (!file_exists($archivoImagen) || filesize($archivoImagen) < 1000) {
    file_put_contents("resultado_actual.txt", "❌ La imagen está vacía o dañada");
    echo json_encode(["error" => "Imagen vacía o dañada"]);
    exit;
}

// Rutas necesarias
$modelo = basename($data['modelo']); // Seguridad
$modeloPath = realpath("../models/$modelo");
$imagenPath = realpath($archivoImagen);
$scriptPath = realpath("../predecir_emocion_tiempo_real.py");
$pythonPath = realpath("../venv/Scripts/python.exe");

// Validar que todos los archivos existan
if (!$modeloPath || !$imagenPath || !$scriptPath || !$pythonPath) {
    file_put_contents("resultado_actual.txt", "❌ Error al resolver rutas");
    echo json_encode([
        "error" => "No se resolvieron correctamente las rutas",
        "modeloPath" => $modeloPath,
        "imagenPath" => $imagenPath,
        "scriptPath" => $scriptPath,
        "pythonPath" => $pythonPath
    ]);
    exit;
}

// Ejecutar el script de predicción
$comando = "\"$pythonPath\" \"$scriptPath\" \"$modeloPath\" \"$imagenPath\"";
$salida = shell_exec($comando);

// Guardar salida sin procesar por si se necesita debug
file_put_contents("debug_salida.txt", $salida);

// Intentar interpretar salida como JSON
// Guardar salida completa por si acaso
file_put_contents("debug_salida.txt", $salida);

// Limpiar y dividir por líneas
$lineas = explode("\n", trim($salida));
$ultimaLinea = trim(end($lineas));

// Intentar decodificar solo la última línea (donde está el JSON)
$resultado = json_decode($ultimaLinea, true);

// Procesar si es válido
if ($resultado && isset($resultado['emocion']) && isset($resultado['probabilidad'])) {
    $linea = "{$resultado['emocion']} ({$resultado['probabilidad']}%)";
    file_put_contents("resultado_actual.txt", $linea);
    echo json_encode($resultado);
} else {
    file_put_contents("resultado_actual.txt", "⚠️ No se pudo interpretar el resultado");
    echo json_encode([
        "error" => "No se pudo decodificar la última línea",
        "debug" => $ultimaLinea
    ]);
}


// Si es válida, guardar resultado limpio y devolverlo
if ($resultado && isset($resultado['emocion']) && isset($resultado['probabilidad'])) {
    $linea = "{$resultado['emocion']} ({$resultado['probabilidad']}%)";
    file_put_contents("resultado_actual.txt", $linea);
    echo json_encode($resultado);
} else {
    file_put_contents("resultado_actual.txt", "⚠️ No se pudo interpretar el resultado");
    echo json_encode([
        "error" => "Salida no válida del script",
        "raw" => $salida
    ]);
}
