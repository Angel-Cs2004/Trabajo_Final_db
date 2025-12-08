<?php
$pageTitle = "Crear Negocio";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
    <div class="bg-white w-full max-w-6xl mx-auto rounded-lg shadow-lg">

        <!-- Encabezado -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded mr-3">
                    <!-- Icono carrito -->
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 6m12-6l2 6m-6-6v6" />
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Crear mi negocio</h1>
            </div>
        </div>

        <!-- Formulario -->
        <form method="POST"
              action="index.php?c=negocio&a=guardar_prop"
              class="px-6 py-6 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Columna izquierda: información negocio -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Información del negocio</h2>

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            * Nombre del negocio
                        </label>
                        <input type="text" name="nombre"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-green-500"
                               placeholder="Ej. Tilapias doña Rosita" required>
                    </div>

                    <!-- Estado -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            * Estado
                        </label>
                        <select name="estado"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-green-500">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción / slogan
                        </label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-green-500"
                                  placeholder="Describe brevemente tu negocio..."></textarea>
                    </div>
                </div>

                <!-- Columna derecha: horarios -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Horarios</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                * Hora apertura
                            </label>
                            <input type="time" name="hora_apertura"
                                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-green-500"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                * Hora cierre
                            </label>
                            <input type="time" name="hora_cierre"
                                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-green-500"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                <a href="index.php?c=negocio&a=misNegocios"
                   class="px-4 py-2 text-sm border rounded text-gray-700 hover:bg-gray-100">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded hover:bg-green-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
