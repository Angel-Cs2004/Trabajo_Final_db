<?php
$pageTitle = "Crear Categoría";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-3xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear categoría</h3>

        <form action="index.php?c=categorias&a=guardar" method="POST" class="space-y-6">

            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input type="text" name="nombre"
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción:</label>
                <textarea name="descripcion"
                          class="w-full border rounded-lg px-4 py-2 min-h-[80px]"></textarea>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="activo" value="1"
                       class="w-5 h-5 text-green-600 border rounded">
                <label class="ml-2 text-sm font-medium">Activa</label>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="index.php?c=categorias&a=listar"
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

<?php require __DIR__ . '/../layouts/footer.php'; ?>
