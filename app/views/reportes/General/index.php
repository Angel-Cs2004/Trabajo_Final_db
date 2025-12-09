<?php
// app/views/reportes/general/index.php
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

        <!-- Filtros -->
        <div class="px-6 py-4 border-b border-gray-200">
            <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                <div>
                    <label class="block text-sm font-medium mb-1">Precio mínimo</label>
                    <input type="number" step="0.01" min="0"
                           name="precio_min"
                           value="<?= htmlspecialchars($filtros['precio_min']) ?>"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Precio máximo</label>
                    <input type="number" step="0.01" min="0"
                           name="precio_max"
                           value="<?= htmlspecialchars($filtros['precio_max']) ?>"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Negocio (opcional)</label>
                    <select name="id_negocio"
                            class="w-full border rounded px-3 py-2 text-sm">
                        <option value="0">Todos los negocios</option>
                        <?php foreach ($negocios as $neg): ?>
                            <option value="<?= $neg['id_negocio'] ?>"
                                <?= ($filtros['id_negocio'] == $neg['id_negocio']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($neg['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                        Generar
                    </button>
                </div>

            </form>
        </div>

        <!-- Tabla de resultados -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Prod.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Negocio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Negocio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($resultados)): ?>
                        <?php foreach ($resultados as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3"><?= htmlspecialchars($row['id_producto']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['producto']) ?></td>
                                <td class="px-6 py-3">S/ <?= number_format((float)$row['precio'], 2) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['categoria']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['id_negocio']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['negocio']) ?></td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded
                                        <?= ($row['estado'] === 'activo') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= htmlspecialchars($row['estado']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">
                                No hay resultados. Ajusta los filtros y vuelve a generar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
