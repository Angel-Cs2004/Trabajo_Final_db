<?php
$pageTitle = "Crear Producto";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear Producto</h3>

        <!-- OJO: ya no necesitamos enctype="multipart/form-data" -->
        <form action="index.php?c=productoNegocio&a=guardar"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre"
                       type="text"
                       class="w-full border rounded-lg px-4 py-2"
                       required>
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium mb-1">* Categoría:</label>
                <select name="id_categoria"
                        class="w-full border rounded-lg px-4 py-2"
                        required>
                    <option value="">-Seleccione-</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= (int)$cat['id_categoria'] ?>">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium mb-1">* Precio:</label>
                <input name="precio"
                       type="number"
                       step="0.01"
                       min="0"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="ej. 14.50"
                       required>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium mb-1">* Estado:</label>
                <select name="estado"
                        class="w-full border rounded-lg px-4 py-2"
                        required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <!-- Negocio (ojo: tu guardar() ahora mismo NO usa este campo) -->
            <div>
                <label class="block text-sm font-medium mb-1">* Negocio:</label>
                <select name="id_negocio"
                        class="w-full border rounded-lg px-4 py-2"
                        required>
                    <option value="">-Seleccione-</option>
                    <?php foreach ($negocios as $neg): ?>
                        <option value="<?= (int)$neg['id_negocio'] ?>">
                            <?= htmlspecialchars($neg['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- URL Imagen -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">
                    URL de la imagen (200px * 300px):
                </label>

                <input type="text"
                       name="url_imagen"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="https://ejemplo.com/mi-imagen.jpg">
                <p class="text-xs text-gray-500 mt-1">
                    Pega aquí la URL directa de la imagen del producto.
                </p>
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
