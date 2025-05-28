<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

// Verificar autenticación
if (!is_authenticated()) {
    http_response_code(403);
    exit('No autorizado');
}

// Obtener parámetros de filtrado
$buscar = $_GET['buscar'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$estado = $_GET['estado'] ?? '';

// Construir condiciones de búsqueda
$where = ['visible' => true];

if (!empty($buscar)) {
    $where['OR'] = [
        'nombre[~]' => $buscar,
        'descripcion[~]' => $buscar
    ];
}

if (!empty($categoria)) {
    $where['categoria'] = $categoria;
}

if (!empty($estado)) {
    $where['estado'] = $estado;
}

// Obtener productos
$productos = $database->select('productos', '*', [
    'AND' => $where,
    'ORDER' => ['fecha_creacion' => 'DESC']
]);

// Si no hay productos, mostrar mensaje
if (empty($productos)) {
    echo '<div class="col-span-full text-center py-8 text-gray-500">'
       . 'No se encontraron productos'
       . '</div>';
    exit;
}
?>
<!-- Envolvemos todo en un contenedor con x-data -->
<div x-data="{ showModal: false, imageSrc: '', imageAlt: '' }" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
    <!-- Modal para visualización de imágenes -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
         @click.away="showModal = false">
        
        <!-- Overlay de fondo oscuro -->
        <div class="fixed inset-0 bg-black opacity-50"></div>
        
        <!-- Contenedor del modal -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg max-w-4xl w-full">
                <!-- Botón cerrar -->
                <button @click="showModal = false"
                        class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Imagen -->
                <div class="p-4">
                    <img :src="imageSrc" :alt="imageAlt" class="max-h-[80vh] w-auto mx-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- Bucle de productos dentro del contexto de x-data -->
    <?php foreach ($productos as $producto): 
        $estadoClase = [
            'activo' => 'bg-green-100 text-green-800',
            'inactivo' => 'bg-gray-100 text-gray-800',
            'agotado' => 'bg-red-100 text-red-800'
        ][$producto['estado']] ?? 'bg-gray-100 text-gray-800';
    ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (!empty($producto['imagen_url'])): ?>
            <img src="../<?= htmlspecialchars($producto['imagen_url'], ENT_QUOTES, 'UTF-8') ?>" 
                 alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" 
                 @click="showModal = true; imageSrc = &quot;../<?= htmlspecialchars($producto['imagen_url'], ENT_QUOTES, 'UTF-8') ?>&quot;; imageAlt = &quot;<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>&quot;" 
                 class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity">
            <?php else: ?>
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-500">
                Imagen no disponible
            </div>
            <?php endif; ?>
            
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>
                    <span class="px-2 py-1 rounded-full text-xs <?= $estadoClase ?>">
                        <?= ucfirst(htmlspecialchars($producto['estado'], ENT_QUOTES, 'UTF-8')) ?>
                    </span>
                </div>
                
                <p class="text-gray-600 text-sm mb-4">
                    <?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-blue-600">
                        $<?= number_format($producto['precio'], 2) ?>
                    </span>
                    <span class="text-sm text-gray-500">
                        Stock: <?= htmlspecialchars($producto['stock'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
                
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="editar.php?id=<?= $producto['id'] ?>" 
                       class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Editar
                    </a>
                    <button onclick="if(confirm('¿Eliminar este producto?')) htmx.ajax('DELETE', 'script_eliminar.php?id=<?= $producto['id'] ?>', '#productos-lista')" 
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>