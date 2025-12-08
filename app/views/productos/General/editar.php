<?php
$pageTitle = "Editar Producto (General)";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-8 py-10 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Editar Producto (General)</h3>

        <form action="index.php?c=productoGeneral&a=actualizar"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre"
                       value="<?= htmlspecialchars($producto['nombre']) ?>"
                       type="text"
                       class="w-full border rounded-lg px-4 py-2"
                       required>
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium mb-1">* Precio:</label>
                <input name="precio"
                       value="<?= htmlspecialchars($producto['precio']) ?>"
                       type="number"
                       step="0.01"
                       class="w-full border rounded-lg px-4 py-2"
                       required>
            </div>

            <!-- Imagen -->
            <div>
                <label class="block text-sm font-medium mb-1">URL Imagen:</label>
                <input name="url_imagen"
                       value="<?= htmlspecialchars($producto['url_imagen'] ?? '') ?>"
                       type="text"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium mb-1">* Categoría:</label>
                <select name="id_categoria"
                        class="w-full border rounded-lg px-4 py-2"
                        required>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>"
                            <?= $producto['id_categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Negocio -->
            <div>
                <label class="block text-sm font-medium mb-1">* Negocio:</label>
                <select name="id_negocio"
                        class="w-full border rounded-lg px-4 py-2"
                        required>
                    <?php foreach ($negocios as $neg): ?>
                        <option value="<?= $neg['id_negocio'] ?>"
                            <?= $producto['id_negocio'] == $neg['id_negocio'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($neg['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium mb-1">* Estado:</label>
                <select name="estado" class="w-full border rounded-lg px-4 py-2">
                    <option value="activo"   <?= $producto['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= $producto['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="col-span-2 flex justify-end gap-3 mt-6">
                <a href="index.php?c=productoGeneral&a=listar"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg">
                    Cerrar
                </a>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    Guardar
                </button>
            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
