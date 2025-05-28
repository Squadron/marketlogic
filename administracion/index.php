<?php
session_start();
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/htmx.min.js"></script>
    <script defer src="../js/alpine.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex space-x-4">
                    <a href="../" 
                       class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                        <span class="font-semibold text-lg">Market Logic</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="nuevo_usuario.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Nuevo Usuario
                    </a>
                    <a href="nuevo_producto.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Bloque de búsqueda de usuarios -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-lg font-semibold mb-4">Buscar Usuarios:</p>
            <form hx-get="script_buscar_usuarios.php" hx-target="#resultados-usuarios" hx-swap="innerHTML">
                <div class="flex space-x-4 mb-4">
                    <input type="text" name="nombre" placeholder="Buscar por nombre..." class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    <input type="text" name="correo" placeholder="Buscar por correo..." class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Buscar Usuarios
                </button>
            </form>
        </div>

        <!-- Resultados de búsqueda de usuarios -->
        <div id="resultados-usuarios" class="bg-white rounded-lg shadow-md p-6">
            <!-- Aquí se mostrarán los resultados de la búsqueda de usuarios -->
        </div>

        <div class="h-7"></div>
        <!-- Bloque de búsqueda de productos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6" x-data="{
            categorias: [],
            estados: [],
            visibilidades: [],
            cargarOpciones() {
                fetch('script_obtener_opciones.php')
                    .then(response => response.json())
                    .then(data => {
                        this.categorias = data.categorias;
                        this.estados = data.estados;
                        this.visibilidades = data.visibilidades;
                    })
                    .catch(error => console.error('Error al cargar las opciones:', error));
            }
        }" x-init="cargarOpciones()">
            <p class="text-lg font-semibold mb-4">Buscar Productos:</p>
            <form hx-get="script_buscar_productos.php" hx-target="#resultados-productos" hx-swap="innerHTML">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <input type="text" name="nombre" placeholder="Buscar por nombre..." class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    <select name="categoria" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                        <option value="">Categoría [Cualquiera]</option>
                        <template x-for="categoria in categorias" :key="categoria">
                            <option :value="categoria" x-text="categoria"></option>
                        </template>
                    </select>
                    <input type="number" name="precio_min" placeholder="Precio mínimo..." class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    <input type="number" name="precio_max" placeholder="Precio máximo..." class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    <select name="estado" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                        <option value="">Estado [Cualquiera]</option>
                        <template x-for="estado in estados" :key="estado">
                            <option :value="estado" x-text="estado"></option>
                        </template>
                    </select>
                    <select name="visible" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                        <option value="">Visibilidad [Cualquiera]</option>
                        <template x-for="(texto, valor) in visibilidades" :key="valor">
                            <option :value="valor" x-text="texto"></option>
                        </template>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Buscar Productos
                </button>
            </form>
        </div>

        <!-- Resultados de búsqueda de productos -->
        <div id="resultados-productos" class="bg-white rounded-lg shadow-md p-6">
            <!-- Aquí se mostrarán los resultados de la búsqueda de productos -->
        </div>
    </div>
</body>
</html>