<?php
$pageTitle = "Categorías";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <?php if (!empty($_SESSION['flash_msg'])): ?>
    <?php
        $tipo = $_SESSION['flash_tipo'] ?? 'success';
        $msg  = $_SESSION['flash_msg'] ?? '';
        unset($_SESSION['flash_msg'], $_SESSION['flash_tipo']);

        $clase = ($tipo === 'success')
            ? 'bg-green-50 border-green-300 text-green-800'
            : 'bg-red-50 border-red-300 text-red-800';
    ?>

    <div class="mx-0 mb-4 border rounded-lg px-4 py-3 <?= $clase ?>">
        <div class="flex items-start justify-between">
            <div class="text-sm font-medium">
                <?= htmlspecialchars($msg) ?>
            </div>

            <button type="button"
                    class="ml-4 text-lg leading-none opacity-60 hover:opacity-100"
                    onclick="this.closest('div.border').remove()">
                ×
            </button>
        </div>
    </div>
<?php endif; ?>

    <div class="bg-white rounded-lg shadow">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3"></div>
                <h1 class="text-xl font-semibold text-gray-800">Administrar categorías</h1>
            </div>

            <a href="index.php?c=categorias&a=crear"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                <span class="mr-1">+</span> Crear
            </a>
        </div>

        <!-- Controles -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
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
                       placeholder="Por nombre"/>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody id="categoriasTableBody">
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr class="hover:bg-gray-50">

                                <!-- ID -->
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($categoria['id_categoria'] ?? '-') ?>
                                </td>

                                <!-- Nombre -->
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($categoria['nombre'] ?? '-') ?>
                                </td>

                                <!-- Descripción -->
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($categoria['descripcion'] ?? '-') ?>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-3">
                                    <?php if (($categoria['estado'] ?? '') === 'activo'): ?>
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                            Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">
                                            Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-3 space-x-2">
                                    <a href="index.php?c=categorias&a=editar&id_categoria=<?= $categoria['id_categoria'] ?>"
                                       class="bg-purple-900 hover:bg-purple-800 text-white px-3 py-1 rounded text-xs">
                                        Editar
                                    </a>



                                    <a href="index.php?c=categorias&a=eliminar&id_categoria=<?= $categoria['id_categoria'] ?>"
                                       class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                       onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                        Eliminar
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">
                                No se encontraron categorías.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
