<?php
$pageTitle = "Mis Negocios";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white rounded-lg shadow">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3"></div>
                <h1 class="text-xl font-semibold text-gray-800">Mis negocios</h1>
            </div>

            <a href="index.php?c=negocio&a=crearPropietario"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center text-sm font-medium">
                <span class="mr-1">+</span> Crear negocio
            </a>

        </div>

        <!-- Contenido -->
        <div class="px-6 py-6">
            <?php if (!empty($negocios)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($negocios as $negocio): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">
                                <?= htmlspecialchars($negocio['nombre'] ?? '-') ?>
                            </h2>

                            <p class="text-sm text-gray-600 mb-2">
                                <?= htmlspecialchars($negocio['descripcion'] ?? 'Sin descripción') ?>
                            </p>

                            <p class="text-xs text-gray-500 mb-1">
                                Horario: 
                                <?= htmlspecialchars($negocio['hora_apertura'] ?? '--:--') ?> - 
                                <?= htmlspecialchars($negocio['hora_cierre'] ?? '--:--') ?>
                            </p>

                            <p class="text-xs mb-3">
                                Estado:
                                <?php if (($negocio['estado'] ?? '') === 'activo'): ?>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </p>

                            <div class="flex justify-between items-center mt-3">
                                <a href="index.php?c=negocio&a=perfil&id=<?= $negocio['id_negocio'] ?>"
                                   class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    Ver perfil
                                </a>

                                <a href="index.php?c=negocio&a=perfil&id=<?= $negocio['id_negocio'] ?>"
                                class="text-xs bg-purple-900 hover:bg-purple-800 text-white px-3 py-1 rounded">
                                    Editar
                                </a>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-gray-500">
                    Aún no tienes negocios registrados.
                </p>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
