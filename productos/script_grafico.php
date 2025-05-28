<?php
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

if (!is_authenticated()) {
    http_response_code(403);
    echo '<div class="text-red-500">No autorizado</div>';
    exit;
}

try {
    // Consultar estadísticas de productos
    $estadisticas = $database->select('productos_estadisticas', [
        '[>]productos' => ['producto_id' => 'id']
    ], [
        'productos.nombre',
        'productos_estadisticas.ventas',
        'productos_estadisticas.ingresos'
    ]);

    if (empty($estadisticas)) {
        echo '<div class="text-gray-500">No hay datos disponibles para mostrar en la gráfica.</div>';
        exit;
    }

    // Preparar datos para la gráfica
    $labels = [];
    $ventas = [];
    $ingresos = [];

    foreach ($estadisticas as $estadistica) {
        $labels[] = $estadistica['nombre'];
        $ventas[] = $estadistica['ventas'];
        $ingresos[] = $estadistica['ingresos'];
    }
    ?>

    <canvas id="grafica-productos" 
            data-labels='<?php echo json_encode($labels); ?>' 
            data-ventas='<?php echo json_encode($ventas); ?>' 
            data-ingresos='<?php echo json_encode($ingresos); ?>'>
    </canvas>
    <?php
} catch (Exception $e) {
    echo '<div class="text-red-500">Error al cargar los datos: ' . htmlspecialchars($e->getMessage()) . '</div>';
}