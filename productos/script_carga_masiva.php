<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

require_once __DIR__ . '/../vendor/autoload.php';

// Verificar autenticación
if (!is_authenticated()) {
    http_response_code(403);
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">No autorizado.</span>
          </div>';
    exit;
}

// Verificar que se haya subido un archivo
if (!isset($_FILES['archivo_excel']) || $_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">No se pudo cargar el archivo.</span>
          </div>';
    exit;
}

// Validar tipo de archivo
$allowedTypes = [
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

if (!in_array($_FILES['archivo_excel']['type'], $allowedTypes)) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">El archivo debe ser un documento Excel válido.</span>
          </div>';
    exit;
}

// Procesar el archivo Excel
try {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['archivo_excel']['tmp_name']);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    // Validar si el archivo contiene las columnas esperadas
    $expectedColumns = ['nombre', 'descripcion', 'precio', 'stock', 'estado', 'visible', 'categoria'];
    $header = array_map('strtolower', array_map('trim', $rows[0] ?? []));
    if (array_diff($expectedColumns, $header)) {
        throw new Exception("El archivo no contiene las columnas esperadas: " . implode(', ', $expectedColumns));
    }

    // Remover encabezados
    array_shift($rows);

    $exitosos = 0;
    $errores = 0;
    $mensajesError = [];

    foreach ($rows as $index => $row) {
        // Validar datos requeridos
        if (empty($row[0]) || !is_numeric($row[2]) || !is_numeric($row[3])) {
            $errores++;
            $mensajesError[] = "Fila " . ($index + 2) . ": Datos inválidos o faltantes (nombre, precio, stock son obligatorios)";
            continue;
        }

        // Preparar datos para inserción
        $producto = [
            'nombre' => trim($row[0]),
            'descripcion' => trim($row[1] ?? ''),
            'precio' => (float)$row[2],
            'stock' => (int)$row[3],
            'estado' => in_array(strtolower(trim($row[4] ?? '')), ['activo', 'inactivo', 'agotado']) 
                      ? strtolower(trim($row[4])) 
                      : 'activo',
            'visible' => isset($row[5]) && in_array(strtolower(trim($row[5])), ['1', 'true', 'si', 'yes']) ? 1 : 0,
            'categoria' => trim(strtolower($row[6] ?? '')),
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        // Validar datos requeridos y formato
        if (empty($producto['nombre']) || 
            !is_numeric($producto['precio']) || 
            !is_numeric($producto['stock']) || 
            empty($producto['categoria'])) {
            $errores++;
            $mensajesError[] = "Fila " . ($index + 2) . ": Datos inválidos o faltantes (nombre, precio, stock, categoria son obligatorios)";
            continue;
        }

        // Insertar en la base de datos
        try {
            $database->insert('productos', $producto);
            $exitosos++;
        } catch (Exception $e) {
            $errores++;
            $mensajesError[] = "Fila " . ($index + 2) . ": Error al insertar en la base de datos - " . $e->getMessage();
        }
    }

    // Proporcionar retroalimentación al usuario
    echo '<div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Éxito:</strong>
            <span class="block sm:inline">Carga completada: ' . $exitosos . ' registros exitosos, ' . $errores . ' errores.</span>
          </div>';

    if (!empty($mensajesError)) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Errores:</strong>
                <ul>';
        foreach ($mensajesError as $mensaje) {
            echo '<li>' . htmlspecialchars($mensaje) . '</li>';
        }
        echo '</ul></div>';
    }
} catch (Exception $e) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">' . htmlspecialchars($e->getMessage()) . '</span>
          </div>';
}
?>

<script>
// Hacer que los mensajes de éxito desaparezcan después de 2 segundos
setTimeout(() => {
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        successMessage.style.transition = 'opacity 0.5s ease';
        successMessage.style.opacity = '0';
        setTimeout(() => successMessage.remove(), 500);
    }
}, 2000);
</script>