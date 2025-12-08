<?php
$pageTitle = "Productos (General)";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-6">

    <div class="bg-white rounded-lg shadow">

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3">
                    <i class="bi bi-collection text-green-700 text-xl"></i>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Productos Generales</h1>
            </div>

            <a href="index.php?c=productoGeneral&a=crear"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                <span class="mr-1">+</span> Crear
            </a>
        </div>

        <!-- Controles -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Mostrar</span>
                <select id="recordsPerPage" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span class="text-sm text-gray-600">registros</span>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Buscar:</span>
                <input id="searchInput" type="text"
                       class="border border-gray-300 rounded px-3 py-1.5 text-sm"
                       placeholder="Por nombre o negocio">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Negocio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                            <tr class="border-t">

                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($prod['nombre_negocio']) ?>
                                </td>

                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($prod['nombre']) ?>
                                </td>

                                <td class="px-6 py-3">
                                    S/ <?= number_format($prod['precio'], 2) ?>
                                </td>

                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($prod['nombre_categoria']) ?>
                                </td>

                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded
                                        <?= $prod['estado'] === 'activo'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800' ?>">
                                        <?= htmlspecialchars($prod['estado']) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-3">
                                    <a href="index.php?c=productoGeneral&a=editar&id=<?= $prod['id_producto'] ?>"
                                       class="bg-purple-900 hover:bg-purple-800 text-white px-3 py-1 rounded text-xs">
                                        Editar
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">
                                No se encontraron productos.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
