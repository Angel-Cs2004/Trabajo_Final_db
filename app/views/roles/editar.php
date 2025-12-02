
<?php
$pageTitle = "Editar Rol";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white w-full max-w-lg mx-auto rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Editar rol</h3>

        <form action="index.php?c=roles&a=actualizar" method="POST" class="space-y-4">

            <input type="hidden" name="id_rol" value="<?= $rol['id_rol'] ?>">

            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre" type="text"
                    value="<?= htmlspecialchars($rol['nombre']) ?>"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción:</label>
                <textarea name="descripcion" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($rol['descripcion'] ?? '') ?></textarea>
            </div>


            <div class="flex justify-end gap-2 pt-2">
                <a href="index.php?c=roles&a=index"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Cancelar
                </a>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Actualizar
                </button>
            </div>

        </form>
    </div>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
