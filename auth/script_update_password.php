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
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Obtener datos actuales del usuario
$user = $database->get('usuarios', '*', ['id' => $_SESSION['user_id']]);

// Validar que todos los campos estén llenos
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $errors[] = 'Todos los campos de contraseña son requeridos';
} else {
    // Validar contraseña actual
    if (!password_verify($current_password, $user['password'])) {
        $errors[] = 'La contraseña actual es incorrecta';
    } elseif ($new_password !== $confirm_password) {
        $errors[] = 'Las contraseñas nuevas no coinciden';
    } elseif (strlen($new_password) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
}

if ($errors) {
    // Removemos el código de estado HTTP 422 ya que puede interferir con HTMX
    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
    foreach ($errors as $error) {
        echo "<p>{$error}</p>";
    }
    echo '</div>';
} else {
    $database->update('usuarios', [
        'password' => password_hash($new_password, PASSWORD_DEFAULT)
    ], ['id' => $_SESSION['user_id']]);
    
    echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">';
    echo "<p>Contraseña actualizada exitosamente</p>";
    echo '</div>';
}