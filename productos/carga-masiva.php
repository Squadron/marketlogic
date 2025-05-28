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
    <title>Carga Masiva de Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/htmx.min.js"></script>
    <script src="../js/alpine.min.js" defer></script>
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
                        <span>Volver a Productos</span>
                    </a>
                </div>
                <div class="text-lg font-semibold text-gray-700">Carga Masiva</div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div id="mensaje-container"></div>
            
            <div x-data="{ isUploading: false, progress: 0 }">
                <form hx-post="script_carga_masiva.php" 
                      hx-encoding="multipart/form-data"
                      hx-target="#mensaje-container"
                      @htmx:before-request="isUploading = true"
                      @htmx:after-request="isUploading = false"
                      class="space-y-6">
                
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label class="block text-gray-700 font-medium mb-2">Seleccionar Archivo Excel</label>
                    <input type="file" name="archivo_excel" accept=".xlsx, .xls" 
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100" required>
                    <p class="mt-1 text-sm text-gray-500">Formatos soportados: .xlsx, .xls</p>
                </div>
                
                <!-- Indicador de progreso -->
                <div x-show="isUploading" class="space-y-2">
                    <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-sm text-gray-600 text-center">Procesando archivo...</p>
                </div>
            
                <div class="flex justify-end space-x-3">
                    <a href="index.php" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            x-bind:disabled="isUploading"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isUploading">Procesar Archivo</span>
                        <span x-show="isUploading">Procesando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>