<?php
$pageTitle = "Crear Rol";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white w-full max-w-4xl mx-auto rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Crear rol</h3>

        <form action="index.php?c=roles&a=guardar" method="POST" class="space-y-6">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre" type="text" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Descripción (solo visual) -->


            <!-- Permisos por módulo -->
            <div>
                <label class="block text-sm font-medium mb-2">Permisos por módulo:</label>

                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1 text-center">Usar</th>
                                <th class="border px-2 py-1 text-left">Módulo (tag)</th>
                                <?php foreach ($permisos as $permiso): ?>
                                    <th class="border px-2 py-1 text-center">
                                        <?= htmlspecialchars($permiso['nombre']) ?><br>
                                        <span class="text-xs text-gray-500">
                                            (<?= htmlspecialchars($permiso['CRUD']) ?>)
                                        </span>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                                <tr>
                                    <!-- Checkbox para activar/desactivar el módulo -->
                                    <td class="border px-2 py-1 text-center">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 tag-toggle"
                                            data-tag-id="<?= $tag['id_tag'] ?>"
                                        >
                                    </td>

                                    <td class="border px-2 py-1 font-medium">
                                        <?= htmlspecialchars($tag['modulos']) ?>
                                    </td>

                                    <?php foreach ($permisos as $permiso): ?>
                                        <td class="border px-2 py-1 text-center">
                                            <input
                                                type="checkbox"
                                                name="permisos[<?= $tag['id_tag'] ?>][]"
                                                value="<?= $permiso['id_permiso'] ?>"
                                                class="w-4 h-4 perm-toggle"
                                                data-tag-id="<?= $tag['id_tag'] ?>"
                                            >
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Marca primero la casilla "Usar" para habilitar el módulo, luego elige qué operaciones (CRUD)
                    puede realizar este rol en ese módulo.
                    Si no se marca ningún permiso, ese módulo no se vincula al rol.
                </p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-2 pt-2">
                <a href="index.php?c=roles&a=index"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</main>

<script>
// Habilitar/deshabilitar CRUD según el checkbox del tag
document.addEventListener('DOMContentLoaded', function () {
    const tagToggles = document.querySelectorAll('.tag-toggle');

    tagToggles.forEach(function (tagChk) {
        const tagId = tagChk.dataset.tagId;
        const perms = document.querySelectorAll(
            '.perm-toggle[data-tag-id="' + tagId + '"]'
        );

        function updateRow() {
            const enabled = tagChk.checked;
            perms.forEach(function (permChk) {
                permChk.disabled = !enabled;
                if (!enabled) {
                    permChk.checked = false;
                }
            });
        }

        // Estado inicial: deshabilitado
        updateRow();

        tagChk.addEventListener('change', updateRow);
    });
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
