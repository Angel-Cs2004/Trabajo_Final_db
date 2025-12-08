<?php
$pageTitle = "Crear Negocio";
require __DIR__ . '/../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">

    <div class="bg-white w-full max-w-5xl mx-auto rounded-lg shadow-lg p-8">

        <h3 class="text-xl font-semibold mb-6">Crear negocio</h3>

        <form action="index.php?c=negocio&a=guardar"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium mb-1">* Nombre del negocio:</label>
                <input type="text"
                       name="nombre"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="ej. Local Del Sabor"
                       required>
            </div>

            <!-- Descripción / slogan -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Descripción / slogan:</label>
                <textarea name="descripcion"
                          class="w-full border rounded-lg px-4 py-2 min-h-[100px]"
                          placeholder="Describe tu negocio en pocas palabras..."></textarea>
            </div>

            <!-- Disponibilidad local (solo visual, no se guarda) -->
            <div>
                <label class="block text-sm font-medium mb-1">Disponibilidad local:</label>
                <select class="w-full border rounded-lg px-4 py-2" disabled>
                    <option>Se calcula según horario</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    La disponibilidad se deriva de la hora actual vs. horario de apertura/cierre.
                </p>
            </div>

            <!-- Estado (usa el campo 'estado' de la tabla) -->
            <div>
                <label class="block text-sm font-medium mb-1">* Estado:</label>
                <select name="estado"
                        class="w-full border rounded-lg px-4 py-2"
                        required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <!-- Horario de atención -->
            <div>
                <label class="block text-sm font-medium mb-1">* Hora de apertura:</label>
                <input type="time"
                       name="hora_apertura"
                       class="w-full border rounded-lg px-4 py-2"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">* Hora de cierre:</label>
                <input type="time"
                       name="hora_cierre"
                       class="w-full border rounded-lg px-4 py-2"
                       required>
            </div>

            <!-- Propietario (solo mostrar, se toma de la sesión) -->
             <div>
                <label class="block text-sm font-medium mb-1">Propietario:</label>
                
                <select name="id_propietario" 
                        class="w-full border rounded-lg px-4 py-2" 
                        required>
                    <option value="">Seleccione un propietario</option>

                    <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= $usuario['id_usuario'] ?>">
                                <?= htmlspecialchars($usuario['nombre']) ?> 
                            </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- URL del logo -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">
                    Logo (200px * 300px) - URL de la imagen:
                </label>

                <input type="text"
                       name="imagen_logo"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="https://mis-imagenes.com/logo-negocio.png">

                <p class="text-xs text-gray-500 mt-1">
                    Pega aquí la URL directa del logo (.jpg, .png, .webp, etc.).
                </p>
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
