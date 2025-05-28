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
    <title>Gestión de Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/htmx.min.js"></script>
    <script defer src="../js/alpine.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="carga-masiva.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Carga Masiva
                    </a>
                    <a href="crear.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        Nuevo Producto
                    </a>
                    <a href="poblar_api.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Poblar desde una API
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form hx-get="script_listar.php" 
                  hx-target="#productos-lista" 
                  hx-trigger="submit, change from:#filtro-categoria, change from:#filtro-estado"
                  class="flex flex-wrap gap-4 items-end">
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="buscar" 
                           class="w-full px-3 py-2 border rounded-lg" 
                           placeholder="Nombre o descripción...">
                </div>
                
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select id="filtro-categoria" name="categoria" 
                            class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Todas</option>
                        <option value="electronica">Electrónica</option>
                        <option value="ropa">Ropa</option>
                        <option value="hogar">Hogar</option>
                    </select>
                </div>
                
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="filtro-estado" name="estado" 
                            class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Todos</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                        <option value="agotado">Agotado</option>
                    </select>
                </div>
                
                <div class="flex-none">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Botón para ver gráfica -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <button id="ver-grafica" 
                    hx-get="script_grafico.php" 
                    hx-target="#grafica-container" 
                    hx-swap="innerHTML"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                Ver Gráfica de Estadísticas
            </button>
            <button id="cerrar-grafica" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                Cerrar Gráfica
            </button>
            <p class="text-sm text-gray-500 mt-4">
                Nota: Solo se muestran en el gráfico los productos con estado <strong>ACTIVO</strong>.
            </p>
        </div>

        <!-- Contenedor para la gráfica -->
        <div id="grafica-container" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
            <!-- Aquí se cargará la gráfica dinámicamente -->
        </div>

        <!-- Lista de Productos -->
        <div id="productos-lista" class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Contenido cargado por HTMX -->
        </div>
    </div>

    <script>
        // Cargar productos al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            htmx.ajax('GET', 'script_listar.php', '#productos-lista');
        });
    </script>

    <script>
        let chartInstance = null; // Variable para almacenar la instancia del gráfico

        // Escuchar el evento htmx:beforeSwap para limpiar el contenedor antes de cargar contenido nuevo
        document.addEventListener('htmx:beforeSwap', function(event) {
            if (event.detail.target.id === 'grafica-container') {
                const container = document.getElementById('grafica-container');

                // Destruir el gráfico existente antes de reemplazar el contenido
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }

                // Limpiar el contenedor
                container.innerHTML = '';
            }
        });

        // Escuchar el evento htmx:afterSwap para inicializar el gráfico
        document.addEventListener('htmx:afterSwap', function(event) {
            if (event.detail.target.id === 'grafica-container') {
                const container = document.getElementById('grafica-container');
                container.classList.remove('hidden');

                // Inicializar el gráfico después de que el contenido se cargue
                const canvas = container.querySelector('canvas');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: JSON.parse(canvas.dataset.labels),
                            datasets: [{
                                label: 'Ventas',
                                data: JSON.parse(canvas.dataset.ventas),
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Ingresos',
                                data: JSON.parse(canvas.dataset.ingresos),
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
        });

        // Agregar funcionalidad al botón de cerrar gráfica
        document.getElementById('cerrar-grafica').addEventListener('click', function() {
            const container = document.getElementById('grafica-container');
            container.classList.add('hidden');

            // Destruir el gráfico existente al cerrar
            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }
        });
    </script>
</body>
</html>