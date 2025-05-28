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

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$errors = [];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

// Obtener datos actuales del usuario
$user = $database->get('usuarios', '*', ['id' => $_SESSION['user_id']]);

$updates = ['nombre_usuario' => $name];

// Validar email único si cambió
if ($email !== $user['email']) {
    $existing_user = $database->get('usuarios', 'id', ['email' => $email]);
    if ($existing_user) {
        $errors[] = 'El correo electrónico ya está registrado';
    } else {
        $updates['email'] = $email;
    }
}

if ($errors) {
    http_response_code(422);
    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
    foreach ($errors as $error) {
        echo "<p>{$error}</p>";
    }
    echo '</div>';
} else {
    $database->update('usuarios', $updates, ['id' => $_SESSION['user_id']]);
    echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">';
    echo "<p>Datos de perfil actualizados exitosamente</p>";
    echo '</div>';
}