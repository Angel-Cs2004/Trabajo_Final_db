<?php
$pageTitle = "Perfil de mis negocios";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="space-y-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">Mis negocios</h1>

        <?php if (!empty($negocios)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($negocios as $n): ?>
                    <div class="bg-white shadow rounded-lg p-5">
                        <h3 class="text-lg font-semibold mb-1">
                            <?= htmlspecialchars($n['nombre']) ?>
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">
                            <?= htmlspecialchars($n['descripcion'] ?? 'Sin descripción') ?>
                        </p>
                        <p class="text-sm text-gray-500">
                            Teléfono: <?= htmlspecialchars($n['telefono'] ?? '-') ?>
                        </p>
                        <p class="mt-2 text-sm">
                            Estado:
                            <span class="px-2 py-1 text-xs rounded
                                <?= ($n['activo'] ?? 0) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= htmlspecialchars($n['estado_disponibilidad'] ?? 'cerrado') ?>
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            Creado el: <?= htmlspecialchars($n['fecha_creacion'] ?? '-') ?>
                        </p>
                        <div class="mt-4 flex justify-end">
                            <a href="index.php?c=negocio&a=editar&id=<?= $n['id_negocio'] ?>"
                               class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded">
                                Editar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600">
                    Aún no tienes negocios registrados.
                </p>
            </div>
        <?php endif; ?><?php
$pageTitle = "Mis Negocios";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="space-y-6">

        <div class="bg-white rounded-lg shadow px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-800">Mis negocios</h1>
            <a href="index.php?c=negocio&a=crear"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                + Crear negocio
            </a>
        </div>

        <?php if (!empty($negocios)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($negocios as $neg): ?>
                    <div class="bg-white rounded-lg shadow p-5">
                        <h2 class="text-lg font-semibold text-gray-800 mb-1">
                            <?= htmlspecialchars($neg['nombre']) ?>
                        </h2>
                        <p class="text-sm text-gray-500 mb-2">
                            <?= htmlspecialchars($neg['descripcion'] ?? 'Sin descripción') ?>
                        </p>

                        <p class="text-xs text-gray-500 mb-1">
                            Teléfono: <?= htmlspecialchars($neg['telefono'] ?? '-') ?>
                        </p>

                        <p class="text-xs text-gray-500 mb-1">
                            Estado:
                            <span class="px-2 py-0.5 text-xs rounded
                                <?= ($neg['activo'] ?? 0) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ($neg['activo'] ?? 0) ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </p>

                        <p class="text-xs text-gray-500 mb-3">
                            Disponibilidad:
                            <span class="px-2 py-0.5 text-xs rounded
                                <?= ($neg['estado_disponibilidad'] ?? '') === 'abierto'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= htmlspecialchars($neg['estado_disponibilidad'] ?? '-') ?>
                            </span>
                        </p>

                        <div class="flex justify-end">
                            <a href="index.php?c=negocio&a=editar&id=<?= $neg['id_negocio'] ?>"
                               class="bg-purple-900 hover:bg-purple-800 text-white px-4 py-1.5 rounded text-xs">
                                Editar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                Aún no tienes negocios registrados.
            </div>
        <?php endif; ?>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>


    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
