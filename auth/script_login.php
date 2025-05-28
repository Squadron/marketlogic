<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Método no permitido');
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Si la petición es HTMX, solo devolver el div de errores
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']);

if (empty($email) || empty($password)) {
    if ($isHtmx) {
        echo '<div class="text-red-500 mt-2">Por favor complete todos los campos</div>';
        exit;
    }
} else {
    $user = $database->get("usuarios", "*", [
        "email" => $email
    ]);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre_usuario'];
        
        if ($isHtmx) {
            header('HX-Redirect: ../index.php');
            exit;
        } else {
            header('Location: ../index.php');
            exit;
        }
    } else {
        if ($isHtmx) {
            echo '<div class="text-red-500 mt-2">Credenciales inválidas</div>';
            exit;
        }
    }
}