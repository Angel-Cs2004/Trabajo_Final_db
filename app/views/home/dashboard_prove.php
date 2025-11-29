<?php
$pageTitle = "Dashboard Proveedor";
require __DIR__ . '/../layouts/header.php';
?>

<div class="space-y-6">

    <h1 class="text-3xl font-bold text-gray-800">
        Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>
    </h1>

    <p class="text-gray-600">
        Desde aquí puedes gestionar tus negocios, productos y visualizar reportes.
    </p>

    <!-- Tarjetas de acceso -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <a href="index.php?c=negocio&a=listar"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-blue-700 text-3xl mb-3">
                <i class="bi bi-shop-window"></i>
            </div>
            <h3 class="text-lg font-semibold">Mis negocios</h3>
            <p class="text-gray-500 text-sm">Gestiona los negocios que administras</p>
        </a>

        <a href="index.php?c=producto&a=listar"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-yellow-600 text-3xl mb-3">
                <i class="bi bi-basket"></i>
            </div>
            <h3 class="text-lg font-semibold">Mis productos</h3>
            <p class="text-gray-500 text-sm">Administra los productos de tus negocios</p>
        </a>

        <a href="index.php?c=reporte&a=productosPorNegocio"
           class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
            <div class="text-red-600 text-3xl mb-3">
                <i class="bi bi-graph-up"></i>
            </div>
            <h3 class="text-lg font-semibold">Reportes</h3>
            <p class="text-gray-500 text-sm">Datos de tus negocios y productos</p>
        </a>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
