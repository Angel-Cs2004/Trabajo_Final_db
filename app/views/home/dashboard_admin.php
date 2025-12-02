<?php
$pageTitle = "Dashboard Administrador";
require __DIR__ . '/../layouts/header.php';
?>
<main class="flex-1 px-8 pt-14 pb-14 overflow-auto">
    <div class="space-y-6">

        <h1 class="text-3xl font-bold text-gray-800">
            Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>
        </h1>

        <p class="text-gray-600">
            Desde aquí puedes gestionar usuarios, negocios, productos y reportes del sistema.
        </p>

        <!-- Tarjetas de acceso  -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Usuarios -->
            <a href="index.php?c=usuarios&a=index"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-green-700 text-3xl mb-3">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="text-lg font-semibold">Usuarios</h3>
                <p class="text-gray-500 text-sm">Administrar usuarios del sistema</p>
            </a>

            <!-- Roles -->
            <a href="index.php?c=roles&a=index"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-purple-700 text-3xl mb-3">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h3 class="text-lg font-semibold">Roles</h3>
                <p class="text-gray-500 text-sm">Administración de roles del sistema</p>
            </a>

            <!-- Negocios -->
            <a href="index.php?c=negocio&a=listar"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-blue-700 text-3xl mb-3">
                    <i class="bi bi-shop"></i>
                </div>
                <h3 class="text-lg font-semibold">Negocios</h3>
                <p class="text-gray-500 text-sm">Administración total de negocios</p>
            </a>

            <!-- Productos -->
            <a href="index.php?c=producto&a=listar"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-yellow-600 text-3xl mb-3">
                    <i class="bi bi-basket"></i>
                </div>
                <h3 class="text-lg font-semibold">Productos</h3>
                <p class="text-gray-500 text-sm">Gestión de productos por negocio</p>
            </a>

            <!-- Categorías -->
            <a href="index.php?c=categoria&a=listar"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-orange-600 text-3xl mb-3">
                    <i class="bi bi-tags"></i>
                </div>
                <h3 class="text-lg font-semibold">Categorías</h3>
                <p class="text-gray-500 text-sm">Clasificación de productos por categorías</p>
            </a>

            <!-- Reportes -->
            <a href="index.php?c=reporte&a=productosPorNegocio"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-red-600 text-3xl mb-3">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <h3 class="text-lg font-semibold">Reportes</h3>
                <p class="text-gray-500 text-sm">Reportes generales del sistema</p>
            </a>

            <a href="index.php?c=negocio&a=listar"
            class="p-6 bg-white shadow rounded-lg hover:shadow-lg transition block">
                <div class="text-blue-700 text-3xl mb-3">
                    <i class="bi bi-shop-window"></i>
                </div>
                <h3 class="text-lg font-semibold">Mi negocio</h3>
                <p class="text-gray-500 text-sm">Edit la informacion del perfil de tu negocio</p>
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
                <h3 class="text-lg font-semibold">Reportes de tu negocio</h3>
                <p class="text-gray-500 text-sm">Datos de tus negocios y productos</p>
            </a>

        </div>


    </div>
</main>





<?php require __DIR__ . '/../layouts/footer.php'; ?>
