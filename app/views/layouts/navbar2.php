<!-- Sidebar Proveedor -->
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

    <nav class="flex-1 mt-4">

        <!-- Mis Negocios -->
        <div class="px-4 mb-2">
            <button
                class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                onclick="toggleSubmenu('submenuNegociosProv', 'iconNegociosProv')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Mis Negocios</span>
                </div>
                <svg id="iconNegociosProv" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="submenuNegociosProv" class="ml-4 mt-2 space-y-1 hidden">
                <a href="index.php?c=negocio&a=listar"
                   class="block px-4 py-2 text-sm hover:bg-green-700 rounded">
                    Negocios
                </a>
            </div>
        </div>

        <!-- Mis Productos -->
        <div class="px-4 mb-2">
            <button
                class="w-full flex items-center justify-between px-4 py-3 hover:bg-green-700 rounded transition"
                onclick="toggleSubmenu('submenuProductosProv', 'iconProductosProv')">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 6m12-6l2 6m-6-6v6"/>
                    </svg>
                    <span>Mis Productos</span>
                </div>
                <svg id="iconProductosProv" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="submenuProductosProv" class="ml-4 mt-2 space-y-1 hidden">
                <a href="#"
                   class="block px-4 py-2 text-sm hover:bg-green-700 rounded">
                    Productos
                </a>
            </div>
        </div>

        <!-- Reportes -->
        <div class="px-4 mb-2">
            <a href="#"
               class="w-full flex items-center px-4 py-3 hover:bg-green-700 rounded transition">
                <i class="bi bi-graph-up mr-3"></i>
                <span>Reportes</span>
            </a>
        </div>

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-green-700">
        <a href="index.php?c=auth&a=logout"
           class="block text-center py-2 bg-red-600 rounded hover:bg-red-700">
            Cerrar sesión
        </a>
    </div>
</div>
