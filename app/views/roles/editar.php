<?php
$pageTitle = "Editar Rol";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white w-full max-w-4xl mx-auto rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Editar rol</h3>

        <form action="index.php?c=roles&a=actualizar" method="POST" class="space-y-6">

            <input type="hidden" name="id_rol" value="<?= htmlspecialchars($rol['id_rol']) ?>">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input
                    name="nombre"
                    type="text"
                    value="<?= htmlspecialchars($rol['nombre']) ?>"
                    class="w-full border rounded px-3 py-2"
                    required
                >
            </div>

            <!-- Descripción (solo visual) -->
            <div>
                <label class="block text-sm font-medium mb-1">Descripción:</label>
                <textarea name="descripcion" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($rol['descripcion'] ?? '') ?></textarea>
            </div>

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
                                <?php
                                    // Ver si este tag ya tiene algún permiso asignado
                                    $tienePermisos = isset($permisosMarcados[$tag['id_tag']]);
                                    $checkedTag = $tienePermisos ? 'checked' : '';
                                ?>
                                <tr>
                                    <!-- Checkbox para activar/desactivar el módulo -->
                                    <td class="border px-2 py-1 text-center">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 tag-toggle"
                                            data-tag-id="<?= $tag['id_tag'] ?>"
                                            <?= $checkedTag ?>
                                        >
                                    </td>

                                    <td class="border px-2 py-1 font-medium">
                                        <?= htmlspecialchars($tag['modulos']) ?>
                                    </td>

                                    <?php foreach ($permisos as $permiso): ?>
                                        <?php
                                            $checkedPerm = isset(
                                                $permisosMarcados[$tag['id_tag']][$permiso['id_permiso']]
                                            ) ? 'checked' : '';
                                        ?>
                                        <td class="border px-2 py-1 text-center">
                                            <input
                                                type="checkbox"
                                                name="permisos[<?= $tag['id_tag'] ?>][]"
                                                value="<?= $permiso['id_permiso'] ?>"
                                                class="w-4 h-4 perm-toggle"
                                                data-tag-id="<?= $tag['id_tag'] ?>"
                                                <?= $checkedPerm ?>
                                            >
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Si desmarcas "Usar" en un módulo, se quitarán todos los permisos de ese módulo para este rol.
                    Igual que antes: solo se guardan los tags que tengan algún permiso marcado.
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
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</main>

<script>
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

        // Estado inicial: si el tag no tiene permisos, deshabilitamos los CRUD
        updateRow();

        tagChk.addEventListener('change', updateRow);
    });
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
