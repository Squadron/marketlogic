<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

// Verificar autenticación
if (!is_authenticated()) {
    http_response_code(403);
    exit('No autorizado');
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

// Verificar ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    exit('ID de producto inválido');
}

$errors = [];

// Validar campos requeridos
if (empty($_POST['nombre'])) {
    $errors[] = 'El nombre es requerido';
}

if (strlen($_POST['nombre']) < 3) {
    $errors[] = 'El nombre debe tener al menos 3 caracteres';
}

if (!isset($_POST['precio']) || $_POST['precio'] < 0) {
    $errors[] = 'El precio debe ser mayor o igual a 0';
}

if (!isset($_POST['stock']) || $_POST['stock'] < 0) {
    $errors[] = 'El stock debe ser mayor o igual a 0';
}

if (empty($_POST['categoria'])) {
    $errors[] = 'La categoría es requerida';
}

if (empty($_POST['estado'])) {
    $errors[] = 'El estado es requerido';
}

// Obtener producto actual
$producto = $database->get('productos', '*', ['id' => $_POST['id']]);
if (!$producto) {
    http_response_code(404);
    exit('Producto no encontrado');
}

// Procesar imagen si se subió una nueva
$imagen_url = $producto['imagen_url']; // Mantener la imagen actual por defecto
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['imagen']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        $errors[] = 'Formato de imagen no permitido. Use: ' . implode(', ', $allowed);
    } else {
        $upload_dir = '../uploads/productos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Eliminar imagen anterior si existe
        if (!empty($producto['imagen_url'])) {
            $imagen_path = __DIR__ . '/../' . $producto['imagen_url'];
            if (file_exists($imagen_path)) {
                unlink($imagen_path);
            }
        }
        
        $newname = uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $newname;
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
            $imagen_url = 'uploads/productos/' . $newname;
        } else {
            $errors[] = 'Error al subir la imagen';
        }
    }
}

if ($errors) {
    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
    foreach ($errors as $error) {
        echo "<p>{$error}</p>";
    }
    echo '</div>';
} else {
    try {
        $database->update('productos', [
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => (float)$_POST['precio'],
            'stock' => (int)$_POST['stock'],
            'categoria' => $_POST['categoria'],
            'estado' => $_POST['estado'],
            'imagen_url' => $imagen_url
        ], [
            'id' => $_POST['id']
        ]);

        echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">';
        echo '<p>Producto actualizado exitosamente</p>';
        echo '</div>';
        echo '<script>setTimeout(function() { window.location = "index.php"; }, 1500);</script>';
    } catch (Exception $e) {
        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
        echo '<p>Error al actualizar el producto</p>';
        echo '</div>';
    }
}