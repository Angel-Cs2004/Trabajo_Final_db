<?php
$pageTitle = "Crear parámetro de imagen";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear parámetro de imagen</h3>

        <form action="index.php?c=parametros&a=guardar" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Etiqueta -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="etiqueta" 
                       type="text" 
                       placeholder="ej. logo, perfil"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium mb-1">* Etiqueta:</label>
                <input name="tipo" 
                       type="text" 
                       placeholder="ej. logo, profile"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Ancho -->
            <div>
                <label class="block text-sm font-medium mb-1">* Ancho(px):</label>
                <input name="ancho_px" 
                       type="number" 
                       placeholder="ej. 200"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Alto -->
            <div>
                <label class="block text-sm font-medium mb-1">* Alto(px):</label>
                <input name="alto_px" 
                       type="number" 
                       placeholder="ej. 200"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Tamaño -->
            <div>
                <label class="block text-sm font-medium mb-1">* Tamaño(KB):</label>
                <input name="tamano_kb" 
                       type="number" 
                       placeholder="ej. 500"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium mb-1">* Categoría:</label>
                <select name="categoria_admin" 
                        class="w-full border rounded-lg px-4 py-2" 
                        required>
                    <option value="">Seleccione</option>
                    <option value="negocios">Negocios</option>
                    <option value="usuarios">Usuarios</option>
                    <option value="productos">Productos</option>
                </select>
            </div>

            <!-- Formatos válidos -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Formatos válidos:</label>
                <input name="formatos_validos" 
                       type="text"
                       placeholder="ej. .jpg,.png,.svg"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Activo -->
            <div class="flex items-center md:col-span-2 pt-2">
                <input type="checkbox" 
                       name="activo" 
                       value="1" 
                       class="w-5 h-5 text-green-600 border rounded">
                <label class="ml-2 text-sm font-medium">Activo</label>
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end gap-3 pt-6">
                
                <a href="index.php?c=parametros&a=index"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg">
                    Cancelar
                </a>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    Guardar
                </button>

            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
