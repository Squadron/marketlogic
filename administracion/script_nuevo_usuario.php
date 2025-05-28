<?php
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

if (!is_authenticated()) {
    http_response_code(403);
    echo '<div class="text-red-500">No autorizado</div>';
    exit;
}

$nombre_usuario = isset($_POST['nombre_usuario']) ? trim($_POST['nombre_usuario']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$password_confirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';

if (empty($nombre_usuario) || empty($email) || empty($password) || empty($password_confirm)) {
    echo '<div class="text-red-500">Todos los campos son obligatorios.</div>';
    exit;
}

if ($password !== $password_confirm) {
    echo '<div class="text-red-500">Las contraseñas no coinciden.</div>';
    exit;
}

try {
    // Verificar si el correo ya existe
    $existe = $database->has('usuarios', ['email' => $email]);
    if ($existe) {
        echo '<div class="text-red-500">El correo ya está registrado.</div>';
        exit;
    }

    // Insertar el nuevo usuario
    $database->insert('usuarios', [
        'nombre_usuario' => $nombre_usuario,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
    ]);

    echo '<div class="text-green-500">Usuario creado exitosamente.</div>';
} catch (Exception $e) {
    echo '<div class="text-red-500">Error al crear el usuario: ' . htmlspecialchars($e->getMessage()) . '</div>';
}