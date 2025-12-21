<?php
$pageTitle = "Productos (General)";
require __DIR__ . '/../../layouts/header.php';

$canCrearProducto  = can('PRODUCTO_GEN', 'C');
$canEditarProducto = can('PRODUCTO_GEN', 'U');

$showAcciones = $canEditarProducto;
?>

<main class="flex-1 px-10 pt-8 pb-14 overflow-auto space-y-6">
    <?php if (!empty($_SESSION['flash_msg'])): ?>
    <div class="mb-4 px-4 py-3 rounded-lg border
        <?= (($_SESSION['flash_tipo'] ?? '') === 'success')
            ? 'bg-green-100 text-green-800 border-green-200'
            : 'bg-red-100 text-red-800 border-red-200' ?>">
        <?= htmlspecialchars($_SESSION['flash_msg']) ?>
    </div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_tipo']); ?>
<?php endif; ?>


    <div class="bg-white rounded-lg shadow">

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3">
                    <i class="bi bi-collection text-green-700 text-xl"></i>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Productos Generales</h1>
            </div>
            <?php if (can('PRODUCTO_GEN', 'C')): ?>
                <a href="index.php?c=productoNegocio&a=crear"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                    <span class="mr-1">+</span> Crear
                </a>
            <?php endif; ?>
        </div>

        <!-- Controles -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Mostrar</span>
                <select id="recordsPerPage" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option>8</option>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>

                        <?php if ($showAcciones): ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                            <tr class="border-t">
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($prod['nombre_negocio'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($prod['nombre'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-3">
                                    S/ <?= number_format((float)($prod['precio'] ?? 0), 2) ?>
                                </td>
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($prod['nombre_categoria'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded
                                        <?= ($prod['estado'] ?? '') === 'activo'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800' ?>">
                                        <?= htmlspecialchars($prod['estado'] ?? 'inactivo') ?>
                                    </span>
                                </td>

                                <?php if ($showAcciones): ?>
                                    <td class="px-6 py-3">
                                        <?php if ($canEditarProducto): ?>
                                            <a href="index.php?c=productoNegocio&a=editar&id_producto=<?= (int)$prod['id_producto'] ?>"
                                               class="bg-purple-900 hover:bg-purple-800 text-white px-3 py-1 rounded text-xs">
                                                Editar
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $showAcciones ? 6 : 5 ?>" class="text-center text-gray-500 py-4">
                                No se encontraron productos.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- PAGINACIÓN -->
            <div class="px-6 py-4 flex justify-between items-center text-sm">
                <span id="paginationInfo" class="text-gray-600"></span>

                <div class="flex space-x-1">
                    <button id="prevPage" class="px-2 py-1 border rounded hover:bg-gray-100">Anterior</button>
                    <span id="currentPage" class="px-3 py-1 border rounded bg-green-100">1</span>
                    <button id="nextPage" class="px-2 py-1 border rounded hover:bg-gray-100">Siguiente</button>
                </div>
            </div>

    </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
