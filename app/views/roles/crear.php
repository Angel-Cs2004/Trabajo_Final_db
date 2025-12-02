
<?php
$pageTitle = "Crear Rol";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white w-full max-w-lg mx-auto rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Crear rol</h3>

        <form action="index.php?c=roles&a=guardar" method="POST" class="space-y-4">

            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre" type="text" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción:</label>
                <textarea name="descripcion" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="index.php?c=roles&a=index"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Cancelar
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
