<?php
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

if (!is_authenticated()) {
    http_response_code(403);
    echo '<div class="text-red-500">No autorizado</div>';
    exit;
}

// Obtener y sanitizar los parámetros de búsqueda
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$email = isset($_GET['correo']) ? trim($_GET['correo']) : ''; // Cambiar 'correo' por 'email'

if (empty($nombre) && empty($email)) {
    echo '<div class="text-gray-500">Por favor, ingrese al menos un criterio de búsqueda.</div>';
    exit;
}

try {
    // Construir la consulta de búsqueda
    $condiciones = [];
    if (!empty($nombre)) {
        $condiciones['nombre_usuario[~]'] = $nombre; // Búsqueda parcial por nombre
    }
    if (!empty($email)) {
        $condiciones['email[~]'] = $email; // Búsqueda parcial por email
    }

    // Consultar la base de datos
    $usuarios = $database->select('usuarios', ['id', 'nombre_usuario', 'email'], $condiciones);

    if (empty($usuarios)) {
        echo '<div class="text-gray-500">No se encontraron usuarios que coincidan con los criterios de búsqueda.</div>';
        exit;
    }

    // Mostrar los resultados
    echo '<h2 class="text-lg font-semibold mb-4">Resultados de Usuarios</h2>';
    echo '<ul class="list-disc pl-5">';
    foreach ($usuarios as $usuario) {
        echo '<li>ID: ' . htmlspecialchars($usuario['id']) . ' - Nombre: ' . htmlspecialchars($usuario['nombre_usuario']) . ' - Correo: ' . htmlspecialchars($usuario['email']) . '</li>';
    }
    echo '</ul>';
} catch (Exception $e) {
    echo '<div class="text-red-500">Error al realizar la búsqueda: ' . htmlspecialchars($e->getMessage()) . '</div>';
}