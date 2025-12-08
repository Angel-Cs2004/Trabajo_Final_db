<h1>Editar: Listado de Negocios</h1>
<?php
// app/views/producto/negocio/editar.php
$pageTitle = "Editar producto de mi negocio";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Editar producto</h3>

        <form action="index.php?c=productoNegocio&a=actualizar"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ID oculto -->
            <input type="hidden" name="id_producto" value="<?= (int)$producto['id_producto'] ?>">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input type="text" name="nombre"
                       value="<?= htmlspecialchars($producto['nombre']) ?>"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <!-- Código -->
            <div>
                <label class="block text-sm font-medium mb-1">* Código:</label>
                <input type="text" name="codigo"
                       value="<?= htmlspecialchars($producto['codigo']) ?>"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium mb-1">* Precio:</label>
                <input type="number" step="0.01" min="0"
                       name="precio"
                       value="<?= htmlspecialchars($producto['precio']) ?>"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <!-- URL imagen -->
            <div>
                <label class="block text-sm font-medium mb-1">URL imagen (opcional):</label>
                <input type="text" name="url_imagen"
                       value="<?= htmlspecialchars($producto['url_imagen'] ?? '') ?>"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium mb-1">* Categoría:</label>
                <select name="id_categoria"
                        class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Seleccione categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>"
                            <?= $cat['id_categoria'] == $producto['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Negocio -->
            <div>
                <label class="block text-sm font-medium mb-1">* Negocio:</label>
                <select name="id_negocio"
                        class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Seleccione negocio</option>
                    <?php foreach ($negocios as $neg): ?>
                        <option value="<?= $neg['id_negocio'] ?>"
                            <?= $neg['id_negocio'] == $producto['id_negocio'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($neg['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Activo -->
            <div class="flex items-center">
                <input type="checkbox" name="activo" value="1"
                       class="w-5 h-5 text-green-600 border rounded"
                       <?= !empty($producto['activo']) ? 'checked' : '' ?>>
                <label class="ml-2 text-sm font-medium">Activo</label>
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end gap-3 pt-6">
                <a href="index.php?c=productoNegocio&a=listar"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg">
                    Cancelar
                </a>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    Actualizar
                </button>
            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
