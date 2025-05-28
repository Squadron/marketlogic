<?php
require_once '../Medoo.php';
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    exit('Método no permitido');
}

// Validar que se recibió un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID de producto inválido');
}

$id = (int)$_GET['id'];

try {
    // Obtener información del producto antes de eliminar
    $producto = $database->get('productos', [
        'id',
        'imagen_url'
    ], [
        'id' => $id
    ]);

    if (!$producto) {
        http_response_code(404);
        exit('Producto no encontrado');
    }

    // Eliminar el archivo de imagen si existe
    if (!empty($producto['imagen_url'])) {
        $imagen_path = __DIR__ . '/../' . $producto['imagen_url'];
        if (file_exists($imagen_path)) {
            unlink($imagen_path);
        }
    }

    // Eliminar el producto de la base de datos
    $result = $database->delete('productos', [
        'id' => $id
    ]);

    if ($result->rowCount() > 0) {
        // Retornar el contenido actualizado de la lista
        require 'script_listar.php';
    } else {
        throw new Exception('No se pudo eliminar el producto');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar el producto: ' . $e->getMessage()
    ]);
}