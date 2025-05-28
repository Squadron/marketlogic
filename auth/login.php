<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';

// Si ya está autenticado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Market Logic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/htmx.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex space-x-4">
                    <a href="../index.php" 
                       class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Inicio</span>
                    </a>
                </div>
                <div class="text-lg font-semibold text-gray-700">Iniciar Sesión</div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Iniciar Sesión</h2>
            
            <form hx-post="script_login.php" hx-target="#error-message" hx-swap="innerHTML">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo Electrónico
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="email"
                           name="email"
                           type="email"
                           required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                           id="password"
                           name="password"
                           type="password"
                           required>
                </div>
                
                <!-- Contenedor para mensajes de error -->
                <div id="error-message"></div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                            type="submit">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <a href="register.php" class="text-sm text-blue-500 hover:text-blue-800">
                    ¿No tienes cuenta? Regístrate
                </a>
            </div>
        </div>
    </div>
</body>
</html>