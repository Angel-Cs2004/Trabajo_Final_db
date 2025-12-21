<!-- Sidebar -->
<div class="w-64 bg-green-800 text-white flex flex-col">

    <!-- Logo -->
    <div class="p-6 bg-green-900">
        <div class="flex items-center justify-center mb-2">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded flex items-center justify-center">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                </svg>
            </div>
        </div>
        <div class="text-center text-xs leading-tight">
            Asociación de Vendedores de Comida San Miguel de
        </div>
        <div class="text-center text-xl font-bold tracking-widest mt-1">
            YAHUARCOCHA
        </div>
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 py-4">

        <!-- HOME (siempre visible) -->
        <div class="px-4 mb-2">
            <a href="index.php?c=home&a=dashboardAdmin"
               class="flex items-center px-4 py-3 hover:bg-green-700 rounded transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9.5l9-7 9 7V20a2 2 0 01-2 2H5a2 2 0 01-2-2V9.5z"/>
                </svg>
                <span>Home</span>
            </a>
        </div>

        <!-- Roles y Usuarios -->
        <?php
            $hasUsuarios = can('USUARIO', 'R');
            $hasRoles    = can('ROL', 'R');

            if ($hasUsuarios && $hasRoles) {
                $labelRolesUsuarios = 'Roles y Usuarios';
            } elseif ($hasRoles) {
                $labelRolesUsuarios = 'Roles';
            } elseif ($hasUsuarios) {
                $labelRolesUsuarios = 'Usuarios';
            }
        ?>

        <?php if ($hasUsuarios || $hasRoles): ?>
        <div class="px-4 mb-2">
            <button class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                    onclick="toggleSubmenu('submenuRoles', 'iconRoles')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span><?= htmlspecialchars($labelRolesUsuarios) ?></span>
                </div>
                <svg id="iconRoles" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="submenuRoles" class="ml-4 mt-2 space-y-1 hidden">
                <?php if (can('USUARIO', 'R')): ?>
                    <a href="index.php?c=usuarios&a=index"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Usuarios</a>
                <?php endif; ?>

                <?php if (can('ROL', 'R')): ?>
                    <a href="index.php?c=roles&a=index"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Roles</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- CONFIGURACIONES (lo dejaste con IMAGEN) -->
        <?php if (can('IMAGEN', 'R')): ?>
        <div class="px-4 mb-2">
            <button class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                    onclick="toggleSubmenu('submenuConf', 'iconConf')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Configuraciones</span>
                </div>
                <svg id="iconConf" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="submenuConf" class="ml-4 mt-2 space-y-1 hidden">
                <a href="index.php?c=parametros&a=index"
                   class="block px-4 py-2 text-sm hover:bg-green-700 rounded">
                    Tamaño imágenes
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- ADMINISTRACIÓN NEGOCIOS -->
        <?php if (can('NEGOCIO_GEN','R') || can('NEGOCIO_PROP','R')): ?>
        <div class="px-4 mb-2">
            <button class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                    onclick="toggleSubmenu('submenuNegocios', 'iconNegocios')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Negocios</span>
                </div>
                <svg id="iconNegocios" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="submenuNegocios" class="ml-4 mt-2 space-y-1 hidden">
                <?php if (can('NEGOCIO_GEN','R')): ?>
                    <a href="index.php?c=negocio&a=listar"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Negocios</a>
                <?php endif; ?>

                <?php if (can('NEGOCIO_PROP','R')): ?>
                    <a href="index.php?c=negocio&a=misNegocios"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Mis negocios</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ADMINISTRACIÓN PRODUCTOS -->
        <?php if (can('PRODUCTO_PROP','R') || can('PRODUCTO_GEN','R') || can('CATEGORIA','R')): ?>
        <div class="px-4 mb-2">
            <button class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                    onclick="toggleSubmenu('submenuProductos', 'iconProductos')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 6m12-6l2 6m-6-6v6" />
                    </svg>
                    <span>Productos</span>
                </div>
                <svg id="iconProductos" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="submenuProductos" class="ml-4 mt-2 space-y-1 hidden">
                <?php if (can('PRODUCTO_PROP','R')): ?>
                    <a href="index.php?c=productoNegocio&a=listar"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Mis productos</a>
                <?php endif; ?>

                <?php if (can('PRODUCTO_GEN','R')): ?>
                    <a href="index.php?c=productoGeneral&a=listar"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Productos</a>
                <?php endif; ?>

                <?php if (can('CATEGORIA','R')): ?>
                    <a href="index.php?c=categorias&a=listar"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Categorías</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- REPORTES -->
        <?php if (can('REPORTE_GEN','R') || can('REPORTE_PROP','R')): ?>
        <div class="px-4 mb-2">
            <button class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                    onclick="toggleSubmenu('submenuReportes', 'iconReportes')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 6m12-6l2 6m-6-6v6" />
                    </svg>
                    <span>Reportes</span>
                </div>
                <svg id="iconReportes" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="submenuReportes" class="ml-4 mt-2 space-y-1 hidden">
                <?php if (can('REPORTE_GEN','R')): ?>
                    <a href="index.php?c=reporte&a=reporteGeneral"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Reportes generales</a>
                <?php endif; ?>

                <?php if (can('REPORTE_PROP','R')): ?>
                    <a href="index.php?c=reporte&a=reporteNegocioMio"
                       class="block px-4 py-2 text-sm hover:bg-green-700 rounded">Reportes tienda</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </nav>

    <!-- LOGOUT -->
    <div class="p-4 border-t border-green-700">
        <a href="index.php?c=auth&a=logout"
           class="block text-center py-2 bg-red-600 rounded hover:bg-red-700">
            Cerrar sesión
        </a>
    </div>

</div>
