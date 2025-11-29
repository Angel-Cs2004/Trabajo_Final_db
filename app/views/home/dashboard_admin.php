<?php
$pageTitle = "Dashboard Administrador";
require __DIR__ . '/../layouts/header.php';
?>

<div class="space-y-6">

    <h1 class="text-3xl font-bold text-gray-800">
        Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>
    </h1>

    <p class="text-gray-600">
        Este es tu panel de administración. Desde aquí puedes gestionar usuarios, negocios, productos y reportes del sistema.
    </p>

    <!-- Tarjetas de acceso  -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <a href="index.php?c=usuarios&a=index"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-green-700 text-3xl mb-3">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="text-lg font-semibold">Usuarios</h3>
            <p class="text-gray-500 text-sm">Administrar usuarios del sistema</p>
        </a>

        <a href="index.php?c=negocio&a=listar"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-blue-700 text-3xl mb-3">
                <i class="bi bi-shop"></i>
            </div>
            <h3 class="text-lg font-semibold">Negocios</h3>
            <p class="text-gray-500 text-sm">Administración total de negocios</p>
        </a>

        <a href="index.php?c=producto&a=listar"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-yellow-600 text-3xl mb-3">
                <i class="bi bi-basket"></i>
            </div>
            <h3 class="text-lg font-semibold">Productos</h3>
            <p class="text-gray-500 text-sm">Gestión de productos por negocio</p>
        </a>

        <a href="index.php?c=reporte&a=productosPorNegocio"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-red-600 text-3xl mb-3">
                <i class="bi bi-bar-chart"></i>
            </div>
            <h3 class="text-lg font-semibold">Reportes</h3>
            <p class="text-gray-500 text-sm">Reportes generales del sistema</p>
        </a>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
