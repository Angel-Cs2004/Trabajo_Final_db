<?php
$pageTitle = "Crear Usuario";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear usuario</h3>

        <form action="index.php?c=usuarios&a=guardar" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre" 
                    type="text" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <!-- Correo -->
            <div>
                <label class="block text-sm font-medium mb-1">* Correo:</label>
                <input name="correo" 
                    type="email" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <!-- Identificación -->
            <div>
                <label class="block text-sm font-medium mb-1">* Identificación:</label>
                <input name="identificacion" 
                    type="text" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-sm font-medium mb-1">* Teléfono:</label>
                <input name="telefono" 
                    type="text" 
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Contraseña -->
            <div>
                <label class="block text-sm font-medium mb-1">* Contraseña:</label>
                <input name="clave" 
                    type="password" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <!-- Rol -->
            <!-- Rol -->
            <div>
                <label class="block text-sm font-medium mb-1">* Rol:</label>
                <select name="rol" 
                        class="w-full border rounded-lg px-4 py-2" 
                        required>
                    <option value="">Seleccionar</option>

                    <?php foreach ($rolesUsuarios as $rol): ?>
                        <option value="<?= htmlspecialchars($rol['nombre']) ?>">
                            <?= htmlspecialchars(ucfirst($rol['nombre'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Estado -->
            <div class="flex items-center col-span-2 pt-2">
                <input name="activo" 
                    type="checkbox" 
                    value="1" 
                    class="w-5 h-5 text-green-600 border rounded">
                <label class="ml-2 text-sm font-medium">Activo</label>
            </div>

            <!-- Botones -->
            <div class="col-span-2 flex justify-end gap-3 pt-6">

                <a href="index.php?c=usuarios&a=index" 
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
