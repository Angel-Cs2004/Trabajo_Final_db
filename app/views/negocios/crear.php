<?php
$pageTitle = "Crear Negocio";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear negocio</h3>

        <form action="index.php?c=negocio&a=guardar" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre:</label>
                <input type="text" 
                       name="nombre" 
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-sm font-medium mb-1">Teléfono:</label>
                <input type="text" 
                       name="telefono"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Descripción:</label>
                <textarea name="descripcion" 
                          class="w-full border rounded-lg px-4 py-2 min-h-[100px]"></textarea>
            </div>

            <!-- Estado -->
            <div class="flex items-center pt-2">
                <input type="checkbox"
                    name="estado_disponibilidad"
                    value="1"
                    class="w-5 h-5 text-green-600 border rounded">
                <label class="ml-2 text-sm font-medium">Activo</label>
            </div>


            <!-- Propietario -->
            <div>
                <label class="block text-sm font-medium mb-1">Propietario:</label>
                
                <select name="id_propietario" 
                        class="w-full border rounded-lg px-4 py-2" 
                        required>
                    <option value="">Seleccione un propietario</option>

                    <?php foreach ($usuarios as $usuario): ?>
                        <?php if ($usuario['rol'] === 'propietario'): ?>
                            <option value="<?= $usuario['id_usuario'] ?>">
                                <?= htmlspecialchars($usuario['nombre']) ?> (<?= htmlspecialchars($usuario['rol']) ?>)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end gap-3 pt-6">

                <a href="index.php?c=negocio&a=listar"
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
