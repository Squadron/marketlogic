<?php
session_start();
require 'Medoo.php';
require 'conex.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Logic</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="js/htmx.min.js"></script>
    <script src="js/alpine.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <a href="index.php" class="flex items-center py-4">
                        <span class="font-semibold text-gray-500 text-lg">Market Logic</span>
                    </a>
                </div>
                <div class="flex items-center space-x-3">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="administracion" class="py-2 px-4 text-gray-500 hover:text-gray-700">Administracion</a>
                        <a href="productos" class="py-2 px-4 text-gray-500 hover:text-gray-700">Productos</a>
                        <a href="auth/profile.php" class="py-2 px-4 text-gray-500 hover:text-gray-700">Perfil</a>
                        <a href="auth/logout.php" class="py-2 px-4 bg-red-500 text-white rounded hover:bg-red-600">Cerrar Sesión</a>
                    <?php else: ?>
                        <a href="auth/login.php" class="py-2 px-4 text-gray-500 hover:text-gray-700">Iniciar Sesión</a>
                        <a href="auth/register.php" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">Registrarse</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800">Bienvenido a Market Logic</h1>
                <p class="mt-4 text-gray-600">Gestiona tus productos y más desde aquí.</p>
                <div class="mt-8">
                    <a href="productos" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">Ir a Productos</a>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800">Market Logic</h1>
                <p class="mt-4 text-gray-600">Inicia sesión o regístrate para comenzar.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>