<?php
session_start();
require_once '../includes/auth_check.php';

// Verificar autenticación
if (!is_authenticated()) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/htmx.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex space-x-4">
                    <a href="index.php" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Volver a Administración</span>
                    </a>
                </div>
                <div class="text-lg font-semibold text-gray-700">Nuevo Usuario</div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div id="mensaje-container"></div>
            
            <form hx-post="script_nuevo_usuario.php" 
                hx-encoding="multipart/form-data"
                hx-target="#mensaje-container"
                class="space-y-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                        minlength="3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                        minlength="6">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirm" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                        minlength="6">
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="index.php" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>