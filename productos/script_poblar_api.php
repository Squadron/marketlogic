<?php
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

if (!is_authenticated()) {
    http_response_code(403);
    echo '<div class="text-red-500">No autorizado</div>';
    exit;
}

$api_url = isset($_POST['api_url']) ? trim($_POST['api_url']) : '';
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 10;

if (empty($api_url) || $cantidad < 1) {
    echo '<div class="text-red-500">La URL de la API y la cantidad son obligatorias.</div>';
    exit;
}

try {
    // Realizar la solicitud a la API
    $response = file_get_contents($api_url);
    $productos = json_decode($response, true);

    if (!$productos) {
        echo '<div class="text-red-500">No se pudieron obtener datos de la API.</div>';
        exit;
    }

    // Limitar la cantidad de productos
    $productos = array_slice($productos, 0, $cantidad);

    // Directorio para almacenar las imágenes
    $upload_dir = '../uploads/productos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Contador de productos importados
    $productos_importados = 0;

    // Insertar productos en la base de datos
    foreach ($productos as $producto) {
        $imagen_url = null;

        // Descargar la imagen si está disponible
        if (!empty($producto['image'])) {
            $ext = pathinfo($producto['image'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($ext), $allowed)) {
                $newname = uniqid() . '.' . $ext;
                $upload_path = $upload_dir . $newname;

                // Descargar y guardar la imagen
                if (file_put_contents($upload_path, file_get_contents($producto['image']))) {
                    $imagen_url = 'uploads/productos/' . $newname;
                }
            }
        }

        // Insertar el producto en la base de datos
        $database->insert('productos', [
            'nombre' => $producto['title'],
            'descripcion' => $producto['description'],
            'precio' => $producto['price'],
            'stock' => rand(1, 100), // Generar stock aleatorio
            'categoria' => $producto['category'],
            'estado' => 'activo',
            'imagen_url' => $imagen_url,
            'visible' => true
        ]);

        // Incrementar el contador de productos importados
        $productos_importados++;
    }

    // Mostrar mensaje de éxito con el número de productos importados
    echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">';
    echo "<p>Importación completada. Se importaron {$productos_importados} productos exitosamente.</p>";
    echo '</div>';
} catch (Exception $e) {
    echo '<div class="text-red-500">Error al importar productos: ' . htmlspecialchars($e->getMessage()) . '</div>';
}