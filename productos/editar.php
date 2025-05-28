<?php
session_start();
require_once '../Medoo.php';
require_once '../conex.php';
require_once '../includes/auth_check.php';

if (!is_authenticated()) {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$producto = $database->get('productos', '*', ['id' => $_GET['id']]);

if (!$producto) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - MarketLogic</title>
    <script src="../js/htmx.min.js"></script>
    <script src="../js/alpine.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Editar Producto</h2>
            
            <form hx-post="script_editar.php" hx-encoding="multipart/form-data" hx-target="#mensaje">
                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                        Nombre *
                    </label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?= htmlspecialchars($producto['nombre']) ?>"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           required minlength="3">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                        Descripción
                    </label>
                    <textarea id="descripcion" name="descripcion"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                              rows="3"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="precio">
                        Precio *
                    </label>
                    <input type="number" id="precio" name="precio" 
                           value="<?= $producto['precio'] ?>"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           required min="0" step="0.01">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">
                        Stock *
                    </label>
                    <input type="number" id="stock" name="stock" 
                           value="<?= $producto['stock'] ?>"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           required min="0">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="categoria">
                        Categoría *
                    </label>
                    <input type="text" id="categoria" name="categoria" 
                           value="<?= htmlspecialchars($producto['categoria']) ?>"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="estado">
                        Estado *
                    </label>
                    <select id="estado" name="estado"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        <option value="activo" <?= $producto['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= $producto['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        <option value="agotado" <?= $producto['estado'] === 'agotado' ? 'selected' : '' ?>>Agotado</option>
                    </select>
                </div>

                <?php if (!empty($producto['imagen_url'])): ?>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Imagen Actual</label>
                    <img src="../<?= htmlspecialchars($producto['imagen_url']) ?>" 
                         alt="Imagen actual" 
                         class="w-32 h-32 object-cover rounded">
                </div>
                <?php endif; ?>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="imagen">
                        Nueva Imagen (opcional)
                    </label>
                    <input type="file" id="imagen" name="imagen"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           accept=".jpg,.jpeg,.png,.gif">
                </div>

                <div id="mensaje"></div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Guardar Cambios
                    </button>
                    <a href="index.php"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>