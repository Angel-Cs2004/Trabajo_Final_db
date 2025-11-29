<?php
$pageTitle = "Crear Usuario";
require __DIR__ . '/../layouts/header.php';
?>

<div class="bg-white w-full max-w-lg mx-auto rounded-lg shadow-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Crear usuario</h3>

    <form action="index.php?c=usuarios&a=guardar" method="POST" class="space-y-4">

        <div>
            <label class="block text-sm font-medium mb-1">* Nombre:</label>
            <input name="nombre" 
                   type="text" 
                   class="w-full border rounded px-3 py-2" 
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">* Correo:</label>
            <input name="correo" 
                   type="email" 
                   class="w-full border rounded px-3 py-2" 
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Identificación:</label>
            <input name="identificacion" 
                   type="text" 
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Teléfono:</label>
            <input name="telefono" 
                   type="text" 
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">* Contraseña:</label>
            <input name="clave" 
                   type="password" 
                   class="w-full border rounded px-3 py-2" 
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">* Rol:</label>
            <select name="rol" 
                    class="w-full border rounded px-3 py-2" 
                    required>
                <option value="">Seleccionar</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="proveedor">Proveedor</option>
            </select>
        </div>

        <div class="flex items-center">
            <input name="activo" 
                   type="checkbox" 
                   value="1" 
                   class="w-4 h-4 text-green-600">
            <label class="ml-2 text-sm font-medium">Activo</label>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="index.php?c=usuarios&a=index" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Cancelar
            </a>

            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Guardar
            </button>
        </div>

    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
