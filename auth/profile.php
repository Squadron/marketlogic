<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

// Obtener datos del usuario actual
$user = $database->get('usuarios', '*', ['id' => $_SESSION['user_id']]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/htmx.min.js"></script>
    <script>
        // Función para manejar los mensajes
        function handleMessage(messageContainer) {
            const message = messageContainer.querySelector('.bg-green-100, .bg-red-100');
            if (message) {
                if (message.classList.contains('bg-green-100')) {
                    // Solo los mensajes de éxito desaparecen automáticamente
                    setTimeout(() => {
                        message.remove();
                    }, 2000);
                }
            }
        }

        // Escuchar las respuestas de HTMX
        document.addEventListener('htmx:afterSwap', function(evt) {
            if (evt.detail.target.id === 'profile-message-container') {
                handleMessage(evt.detail.target);
            }
            if (evt.detail.target.id === 'password-message-container') {
                handleMessage(evt.detail.target);
            }
        });
    </script>
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
                <div class="text-lg font-semibold text-gray-700">Mi Perfil</div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold mb-6">Datos de Perfil</h2>
            
            <div id="profile-message-container"></div>
            
            <form hx-post="script_update_profile.php" hx-target="#profile-message-container" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nombre
                    </label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo htmlspecialchars($user['nombre_usuario'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                           required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                           required>
                </div>
                
                <div>
                    <button type="submit" name="update_profile" value="1"
                            class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:shadow-outline">
                        Actualizar Datos de Perfil
                    </button>
                </div>
            </form>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Cambiar Contraseña</h2>
            
            <div id="password-message-container"></div>
            
            <form hx-post="script_update_password.php" 
                  hx-target="#password-message-container" 
                  class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">
                        Contraseña Actual
                    </label>
                    <input type="password" id="current_password" name="current_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                           required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
                        Nueva Contraseña
                    </label>
                    <input type="password" id="new_password" name="new_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                           required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                        Confirmar Nueva Contraseña
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                           required>
                </div>
                
                <div>
                    <button type="submit" name="update_password" value="1"
                            class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 focus:outline-none focus:shadow-outline">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>