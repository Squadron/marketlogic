<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Método no permitido');
}

$nombre_usuario = $_POST['nombre_usuario'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']);
$error = '';

// Validaciones
if (empty($nombre_usuario) || empty($email) || empty($password) || empty($password_confirm)) {
    $error = 'Por favor complete todos los campos';
} elseif ($password !== $password_confirm) {
    $error = 'Las contraseñas no coinciden';
} elseif (strlen($password) < 6) {
    $error = 'La contraseña debe tener al menos 6 caracteres';
} else {
    // Verificar si el email ya existe
    $exists = $database->get("usuarios", "id", ["email" => $email]);
    if ($exists) {
        $error = 'Este correo electrónico ya está registrado';
    }
}

if ($error) {
    if ($isHtmx) {
        echo '<div class="text-red-500 mt-2">' . htmlspecialchars($error) . '</div>';
        exit;
    }
} else {
    // Crear usuario
    try {
        $database->insert("usuarios", [
            "nombre_usuario" => $nombre_usuario,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
        
        if ($isHtmx) {
            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <p>¡Cuenta creada exitosamente!</p>
                <p class="mt-2">
                    <a href="login.php" class="text-green-700 underline hover:text-green-900">
                        Haga clic aquí para iniciar sesión
                    </a>
                </p>
            </div>';
            exit;
        } else {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit;
        }
    } catch (Exception $e) {
        if ($isHtmx) {
            echo '<div class="text-red-500 mt-2">Error al crear la cuenta. Por favor intente nuevamente.</div>';
            exit;
        }
    }
}