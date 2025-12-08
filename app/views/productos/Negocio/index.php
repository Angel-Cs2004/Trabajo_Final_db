<?php
$pageTitle = "Mis Productos";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3"></div>
                <h1 class="text-xl font-semibold text-gray-800">Mis productos</h1>
            </div>

            <a href="index.php?c=productoNegocio&a=crear"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                <span class="mr-1">+</span> Crear
            </a>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Negocio</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3"><?= htmlspecialchars($prod['nombre']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($prod['codigo']) ?></td>
                                <td class="px-6 py-3">S/ <?= number_format((float)$prod['precio'], 2) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($prod['categoria'] ?? '-') ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($prod['negocio'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">
                                No tienes productos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
