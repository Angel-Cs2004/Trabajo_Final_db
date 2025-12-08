<?php
$pageTitle = "Crear producto";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear producto (mis negocios)</h3>

        <form action="index.php?c=productoNegocio&a=guardar" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input type="text" name="nombre"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <!-- Código -->
            <div>
                <label class="block text-sm font-medium mb-1">* Código:</label>
                <input type="text" name="codigo"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium mb-1">* Precio:</label>
                <input type="number" step="0.01" min="0"
                       name="precio"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <!-- URL Imagen -->
            <div>
                <label class="block text-sm font-medium mb-1">URL imagen (opcional):</label>
                <input type="text" name="url_imagen"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium mb-1">* Categoría:</label>
                <select name="id_categoria"
                        class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Negocio (solo los del usuario) -->
            <div>
                <label class="block text-sm font-medium mb-1">* Negocio:</label>
                <select name="id_negocio"
                        class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($negocios as $n): ?>
                        <option value="<?= $n['id_negocio'] ?>">
                            <?= htmlspecialchars($n['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Activo -->
            <div class="flex items-center md:col-span-2 pt-2">
                <input type="checkbox" name="activo" value="1"
                       class="w-5 h-5 text-green-600 border rounded" checked>
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
                    Guardar
                </button>
            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
