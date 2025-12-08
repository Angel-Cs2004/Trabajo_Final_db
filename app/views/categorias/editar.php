<?php
$pageTitle = "Editar Categoría";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Editar categoría</h3>

        <form action="index.php?c=categorias&a=actualizar" method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ID oculto -->
            <input type="hidden" name="id_categoria"
                   value="<?= htmlspecialchars($categoria['id_categoria']) ?>">

            <!-- Nombre -->
            <div class="md:col-span-2">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre de la categoría
                </label>
                <input type="text" id="nombre" name="nombre" required
                       value="<?= htmlspecialchars($categoria['nombre']) ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                    Descripción
                </label>
                <textarea id="descripcion" name="descripcion" rows="3"
                          class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Estado
                </label>
                <select name="estado"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="activo"   <?= $categoria['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= $categoria['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end gap-3 mt-4">
                <a href="index.php?c=categorias&a=listar"
                   class="px-4 py-2 text-sm rounded border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded bg-green-600 text-white hover:bg-green-700">
                    Actualizar
                </button>
            </div>
        </form>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
