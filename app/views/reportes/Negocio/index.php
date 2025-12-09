<?php
// app/views/reportes/negocio/index.php
$pageTitle = "Reporte por Negocio";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white rounded-lg shadow">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3"></div>
                <h1 class="text-xl font-semibold text-gray-800">Reporte de productos por negocio</h1>
            </div>
        </div>

        <!-- Filtro negocio -->
        <div class="px-6 py-4 border-b border-gray-200">
            <?php if (empty($negocios)): ?>
                <p class="text-sm text-gray-600">
                    No tienes negocios registrados o activos para generar reportes.
                </p>
            <?php else: ?>
                <form method="POST" class="flex flex-col md:flex-row gap-4 items-end">

                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Selecciona un negocio</label>
                        <select name="id_negocio"
                                class="w-full border rounded px-3 py-2 text-sm" required>
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($negocios as $neg): ?>
                                <option value="<?= $neg['id_negocio'] ?>"
                                    <?= ($idNegocioSeleccionado == $neg['id_negocio']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($neg['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                            Ver productos
                        </button>
                    </div>

                </form>
            <?php endif; ?>
        </div>

        <!-- Tabla de productos -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Prod.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3"><?= htmlspecialchars($row['id_producto']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['producto']) ?></td>
                                <td class="px-6 py-3">S/ <?= number_format((float)$row['precio'], 2) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['categoria']) ?></td>
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
                            <td colspan="5" class="text-center text-gray-500 py-4">
                                No hay productos para mostrar. Selecciona un negocio y genera el reporte.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
