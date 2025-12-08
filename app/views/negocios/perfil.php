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
        <?php endif; ?>

    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
