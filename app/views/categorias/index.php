<?php
$pageTitle = "Administración de Usuarios";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white rounded-lg shadow">
    <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3">
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Administrar Categorias</h1>
            </div>

            <a href="index.php?c=categorias&a=crear"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                <span class="mr-1">+</span> Crear
            </a>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>