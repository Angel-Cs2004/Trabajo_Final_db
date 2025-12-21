<?php
$pageTitle = "Administración de Usuarios";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-10 pb-14 overflow-auto">
    <div class="bg-white rounded-lg shadow">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3">
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Administración de Usuarios</h1>
            </div>

            <?php if (can('USUARIO', 'C')): ?>
                <a href="index.php?c=usuarios&a=crear"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                    <span class="mr-1">+</span> Crear
                </a>
            <?php endif; ?>
        </div>

        <!-- Controles -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
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
                       placeholder = "Por nombre">
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Identificación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td class="px-6 py-3"><?= htmlspecialchars($user['nombre'] ?? '-') ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($user['correo'] ?? '-') ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($user['identificacion'] ?? '-') ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($user['telefono'] ?? '-') ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($user['rol'] ?? '-') ?></td>

                            <td class="px-6 py-3">
                                <span class="px-2 py-1 text-xs rounded 
                                <?= ($user['estado'] ?? '') === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ($user['estado'] ?? 'inactivo') ?>
                                </span>
                            </td>

                            <td class="px-6 py-3">
                                <?php if (can('USUARIO', 'U')): ?>
                                <a href="index.php?c=usuarios&a=editar&id=<?= $user['id_usuario'] ?>"
                                   class="bg-purple-900 hover:bg-purple-800 text-white px-3 py-1 rounded text-xs">
                                    Editar
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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

<?php require __DIR__ . '/../layouts/footer.php'; ?>
