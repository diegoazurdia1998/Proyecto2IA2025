<?php
header("Content-Type: text/plain");

$archivo = "resultado_actual.txt";

if (file_exists($archivo)) {
    echo trim(file_get_contents($archivo));
} else {
    echo "⏳ Esperando resultado...";
}
