<?php
$pageTitle = "Actualizar parámetro de imagen";
require __DIR__ . '/../layouts/header.php';
?>

<div class="bg-white w-full max-w-lg mx-auto rounded-lg shadow-lg p-6">

    <h3 class="text-lg font-semibold mb-4">Actualizar parámetro de imagen</h3>

    <form action="index.php?c=parametros&a=actualizar" method="POST" class="space-y-4">

        <input type="hidden" name="id_parametro_imagen" value="<?= $param['id_parametro_imagen'] ?>">

        <div>
            <label class="text-sm font-medium">* Etiqueta:</label>
            <input name="etiqueta" type="text"
                value="<?= htmlspecialchars($param['etiqueta']) ?>"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm font-medium">* Tipo:</label>
            <input name="tipo" type="text"
                value="<?= htmlspecialchars($param['tipo']) ?>"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm font-medium">* Ancho(px):</label>
            <input name="ancho_px" type="number"
                value="<?= htmlspecialchars($param['ancho_px']) ?>"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm font-medium">* Alto(px):</label>
            <input name="alto_px" type="number"
                value="<?= htmlspecialchars($param['alto_px']) ?>"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm font-medium">* Tamaño(KB):</label>
            <input name="tamano_kb" type="number"
                value="<?= htmlspecialchars($param['tamano_kb']) ?>"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm font-medium">* Categoría:</label>
            <select name="categoria_admin" class="w-full border rounded px-3 py-2">
                <option value="negocios"  <?= $param['categoria_admin']=='negocios' ? 'selected':'' ?>>Negocios</option>
                <option value="usuarios"  <?= $param['categoria_admin']=='usuarios' ? 'selected':'' ?>>Usuarios</option>
                <option value="productos" <?= $param['categoria_admin']=='productos'? 'selected':'' ?>>Productos</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Formatos válidos:</label>
            <input name="formatos_validos" type="text"
                value="<?= htmlspecialchars($param['formatos_validos']) ?>"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="activo" value="1"
                class="w-4 h-4 text-green-600"
                <?= $param['activo'] ? 'checked' : '' ?>>
            <label class="ml-2 text-sm font-medium">Activo</label>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="index.php?c=parametros&a=index"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Cancelar</a>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Actualizar
            </button>
        </div>

    </form>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
