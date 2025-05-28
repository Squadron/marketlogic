<?php
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

if (!is_authenticated()) {
    http_response_code(403);
    echo '<div class="text-red-500">No autorizado</div>';
    exit;
}

// Obtener y sanitizar los parámetros de búsqueda
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$precio_min = isset($_GET['precio_min']) ? trim($_GET['precio_min']) : '';
$precio_max = isset($_GET['precio_max']) ? trim($_GET['precio_max']) : '';
$estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';
$visible = isset($_GET['visible']) ? trim($_GET['visible']) : '';

// Depuración: Mostrar los parámetros recibidos (solo para pruebas)
// if (isset($_GET)) {
//     echo '<pre>Parámetros recibidos: ' . print_r($_GET, true) . '</pre>';
// }

// Validar que al menos un criterio de búsqueda esté presente
if (empty($nombre) && empty($categoria) && empty($precio_min) && empty($precio_max) && empty($estado) && empty($visible)) {
    echo '<div class="text-gray-500">Debe especificar al menos 1 criterio de búsqueda.</div>';
    exit;
}

try {
    // Construir la consulta de búsqueda
    $condiciones = [];
    if (!empty($nombre)) {
        $condiciones['nombre[~]'] = $nombre; // Búsqueda parcial por nombre
    }
    if (!empty($categoria)) {
        $condiciones['categoria'] = $categoria; // Búsqueda exacta por categoría
    }
    if (!empty($precio_min)) {
        $condiciones['precio[>=]'] = (float)$precio_min; // Precio mínimo
    }
    if (!empty($precio_max)) {
        $condiciones['precio[<=]'] = (float)$precio_max; // Precio máximo
    }
    if (!empty($estado)) {
        $condiciones['estado'] = $estado; // Estado exacto
    }
    if ($visible !== '') {
        $condiciones['visible'] = (int)$visible; // Visibilidad exacta
    }

    // Consultar la base de datos
    $productos = $database->select('productos', ['id', 'nombre', 'categoria', 'precio', 'stock', 'estado', 'visible'], $condiciones);

    if (empty($productos)) {
        echo '<div class="text-gray-500">No se encontraron productos que coincidan con los criterios de búsqueda.</div>';
        exit;
    }

    // Mostrar los resultados
    echo '<h2 class="text-lg font-semibold mb-4">Resultados de Productos</h2>';
    echo '<ul class="list-disc pl-5">';
    foreach ($productos as $producto) {
        echo '<li>ID: ' . htmlspecialchars($producto['id']) . 
             ' - Nombre: ' . htmlspecialchars($producto['nombre']) . 
             ' - Categoría: ' . htmlspecialchars($producto['categoria']) . 
             ' - Precio: $' . htmlspecialchars($producto['precio']) . 
             ' - Stock: ' . htmlspecialchars($producto['stock']) . 
             ' - Estado: ' . htmlspecialchars($producto['estado']) . 
             ' - Visible: ' . (htmlspecialchars($producto['visible']) ? 'Sí' : 'No') . 
             '</li>';
    }
    echo '</ul>';
} catch (Exception $e) {
    echo '<div class="text-red-500">Error al realizar la búsqueda: ' . htmlspecialchars($e->getMessage()) . '</div>';
}