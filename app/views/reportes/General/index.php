<?php
// app/views/Reportes/General/index.php
$pageTitle = "Reporte General de Productos";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white rounded-lg shadow">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3"></div>
                <h1 class="text-xl font-semibold text-gray-800">Reporte general de productos</h1>
            </div>
        </div>

        <!-- Filtros (GET) -->
        <div class="px-6 py-4 border-b border-gray-200">
            <form method="GET" action="index.php"
                  class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                <input type="hidden" name="c" value="reporte">
                <input type="hidden" name="a" value="reporteGeneral">

                <div>
                    <label class="block text-sm font-medium mb-1">ID Categoría</label>
                    <input type="number" min="0"
                           name="id_categoria"
                           value="<?= htmlspecialchars($idCategoria ?? 0) ?>"
                           class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">
                        Para usar SP: <code>sp_reporte_productos_por_categoria</code>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Precio mínimo</label>
                    <input type="number" step="0.01" min="0"
                           name="precio_min"
                           value="<?= htmlspecialchars($precioMin ?? 0) ?>"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Precio máximo</label>
                    <input type="number" step="0.01" min="0"
                           name="precio_max"
                           value="<?= htmlspecialchars($precioMax ?? 0) ?>"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">ID Negocio (opcional)</label>
                    <input type="number" min="0"
                           name="id_negocio"
                           value="<?= htmlspecialchars($idNegocio ?? 0) ?>"
                           class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">
                        0 = todos los negocios
                    </p>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                        Filtrar
                    </button>
                </div>

            </form>
        </div>

        <!-- Bloque: Productos por categoría -->
        <div class="px-6 py-4">
            <h2 class="text-lg font-semibold mb-3">Productos por categoría</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID Cat.</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID Prod.</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Negocio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($porCategoria)): ?>
                            <?php foreach ($porCategoria as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['id_categoria']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['categoria']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['id_producto']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['producto']) ?></td>
                                    <td class="px-4 py-2">S/ <?= number_format((float)$row['precio'], 2) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['negocio']) ?></td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded
                                            <?= ($row['estado'] === 'activo') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= htmlspecialchars($row['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-3">
                                    No hay datos de categoría. Ingresa un ID de categoría y filtra.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bloque: Productos por rango de precio -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h2 class="text-lg font-semibold mb-3">Productos por rango de precio</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID Prod.</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID Negocio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Negocio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($porRango)): ?>
                            <?php foreach ($porRango as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['id_producto']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['producto']) ?></td>
                                    <td class="px-4 py-2">S/ <?= number_format((float)$row['precio'], 2) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['categoria']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['id_negocio']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['negocio']) ?></td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded
                                            <?= ($row['estado'] === 'activo') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= htmlspecialchars($row['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-3">
                                    No hay datos de rango de precio. Ingresa precios válidos y filtra.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
