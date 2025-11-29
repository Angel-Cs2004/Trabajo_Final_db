<?php
$pageTitle = "Administración de Roles";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-8 pt-14 pb-14 overflow-auto">
    <div class="bg-white rounded-lg shadow">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3">
                    <svg class="w-6 h-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m2 4H7m8-8H7"/>
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Administración de Roles</h1>
            </div>

            <a href="index.php?c=roles&a=crear"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                <span class="mr-1">+</span> Crear
            </a>
        </div>

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
                       class="border border-gray-300 rounded px-3 py-1.5 text-sm" />
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Super Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $rol): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3"><?= htmlspecialchars($rol['nombre']) ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($rol['descripcion'] ?? '-') ?></td>
                            <td class="px-6 py-3">
                                <?= $rol['es_superadmin'] ? '<span class="text-green-700 font-semibold">Sí</span>'
                                                          : '<span class="text-gray-600">No</span>' ?>
                            </td>
                            <td class="px-6 py-3">
                                <a href="index.php?c=roles&a=editar&id=<?= $rol['id_rol'] ?>"
                                   class="bg-purple-900 hover:bg-purple-800 text-white px-3 py-1 rounded text-xs">
                                    Editar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">
                                No se encontraron roles.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
