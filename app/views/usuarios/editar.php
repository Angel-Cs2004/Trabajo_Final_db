<?php
$pageTitle = "Actualizar Usuario";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-8 py-10 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Actualización de Usuario</h3>

        <form action="index.php?c=usuarios&a=actualizar" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input name="nombre" 
                    value="<?= htmlspecialchars($usuario['nombre']) ?>" 
                    type="text" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">* Rol:</label>
                <select name="rol" 
                        class="w-full border rounded-lg px-4 py-2" 
                        required>
                    <option value="">Seleccionar</option>

                    <?php foreach ($rolesUsuarios as $rol): ?>
                        <option 
                            value="<?= htmlspecialchars($rol['nombre']) ?>"
                            <?= $usuario['rol'] === $rol['nombre'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars(ucfirst($rol['nombre'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">* Correo:</label>
                <input name="correo" 
                    value="<?= htmlspecialchars($usuario['correo']) ?>" 
                    type="email" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">* Identificación:</label>
                <input name="identificacion" 
                    value="<?= htmlspecialchars($usuario['identificacion']) ?>" 
                    type="text" 
                    class="w-full border rounded-lg px-4 py-2" 
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Teléfono:</label>
                <input name="telefono" 
                    value="<?= htmlspecialchars($usuario['telefono']) ?>" 
                    type="tel" 
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">* Estado:</label>
                <select name="estado" class="w-full border rounded-lg px-4 py-2">
                    <option value="activo"   <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div class="col-span-2 border-t pt-4">
                <button type="button" 
                        onclick="document.getElementById('pass-section').classList.toggle('hidden')"
                        class="text-sm font-semibold text-gray-600 flex items-center gap-1 mb-3">
                    ▼ Cambiar contraseña
                </button>

                <div id="pass-section" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">

                    <div>
                        <label class="block text-sm font-medium mb-1">Nueva Contraseña:</label>
                        <input name="clave" 
                            type="password" 
                            class="w-full border rounded-lg px-4 py-2">
                    </div>

                </div>
            </div>


            <div class="col-span-2 flex justify-end gap-3 mt-6">
                
                <a href="index.php?c=usuarios&a=index"
                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg">
                    Cerrar
                </a>

                <button type="submit" 
                        name="actualizar"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    Guardar
                </button>

            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
