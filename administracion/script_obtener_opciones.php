<?php
require_once '../Medoo.php';
require_once '../conex.php';

try {
    // Obtener valores únicos de categoría
    $categorias = $database->select('productos', 'categoria', ['categoria[!]' => null, 'GROUP' => 'categoria']);

    // Obtener valores únicos de estado
    $estados = $database->query("SHOW COLUMNS FROM productos LIKE 'estado'")->fetch(PDO::FETCH_ASSOC);
    preg_match("/^enum\((.*)\)$/", $estados['Type'], $matches);
    $estados = array_map(function ($value) {
        return trim($value, "'");
    }, explode(",", $matches[1]));

    // Obtener valores únicos de visibilidad
    $visibilidades = [1 => 'Visible', 0 => 'No Visible'];

    // Respuesta JSON
    echo json_encode([
        'categorias' => $categorias,
        'estados' => $estados,
        'visibilidades' => $visibilidades,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las opciones: ' . $e->getMessage()]);
}