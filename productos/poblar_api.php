<!-- filepath: /d:/laragon/www/marketlogic/productos/poblar_api.php -->
<?php
session_start();
require_once '../includes/auth_check.php';

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
    <title>Poblar desde una API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex space-x-4">
                    <a href="index.php" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Volver a Productos</span>
                    </a>
                </div>
                <div class="text-lg font-semibold text-gray-700">Poblar desde una API</div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div id="mensaje-container" class="relative">
                <!-- Mensaje de carga (oculto por defecto) -->
                <div id="loading-message" style="display: none; position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5); z-index: 50;">
                    <p class="text-white text-lg font-semibold">Cargando...</p>
                </div>
            </div>
            
            <form id="import-form" action="script_poblar_api.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL de la API</label>
                    <input type="url" name="api_url" required
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                           value="https://fakestoreapi.com/products">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad de Productos</label>
                    <input type="number" name="cantidad" required min="1" max="20"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                           value="10">
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="index.php" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Iniciar Importación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('import-form');
            const loadingMessage = document.getElementById('loading-message');
            const mensajeContainer = document.getElementById('mensaje-container');

            // Asegurarse de que el mensaje de carga esté oculto al cargar la página
            loadingMessage.style.display = 'none';

            // Escuchar el evento de envío del formulario
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Evitar el envío normal del formulario

                // Mostrar el mensaje de carga
                loadingMessage.style.display = 'flex';

                // Crear una solicitud AJAX
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Ocultar el mensaje de carga
                    loadingMessage.style.display = 'none';

                    // Mostrar la respuesta del servidor en el contenedor
                    mensajeContainer.innerHTML = data;
                })
                .catch(error => {
                    // Ocultar el mensaje de carga
                    loadingMessage.style.display = 'none';

                    // Mostrar un mensaje de error
                    mensajeContainer.innerHTML = `<div class="text-red-500">Error: ${error.message}</div>`;
                });
            });
        });
    </script>
</body>
</html>