<?php
$pageTitle = "Reporte de Usuarios";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-6">

  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Reporte de Usuarios</h1>
      <p class="text-sm text-gray-500">Filtra usuarios por rol y estado.</p>
    </div>
  </div>

  <!-- Filtros -->
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
      <h2 class="text-lg font-semibold text-gray-800">Filtros</h2>
    </div>

    <div class="px-6 py-5">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="usuarios">

        <!-- Rol -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
          <select name="id_rol" class="w-full border rounded-lg px-3 py-2">
            <option value="0">Todos</option>
            <?php foreach ($roles as $r): ?>
              <option value="<?= (int)$r['id_rol'] ?>"
                <?= ((int)$idRol === (int)$r['id_rol']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Estado -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
          <select name="estado" class="w-full border rounded-lg px-3 py-2">
            <option value="todos"   <?= ($estado === 'todos') ? 'selected' : '' ?>>Todos</option>
            <option value="activo"  <?= ($estado === 'activo') ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo"<?= ($estado === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
          </select>
        </div>

        <!-- Botones -->
        <div class="flex items-end gap-2">
          <button type="submit"
                  class="px-4 py-2 rounded-lg bg-green-700 text-white hover:bg-green-800">
            Aplicar
          </button>

          <!-- PDF con los mismos filtros -->
          <a class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black"
             href="index.php?c=reporte&a=pdfUsuarios&id_rol=<?= (int)$idRol ?>&estado=<?= urlencode($estado) ?>">
            Descargar PDF
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Resultados -->
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h2 class="text-lg font-semibold text-gray-800">Resultados</h2>
      <span class="text-sm text-gray-500">
        Total: <?= count($usuarios) ?>
      </span>
    </div>

    <div class="p-6 overflow-x-auto">
      <?php if (empty($usuarios)): ?>
        <div class="p-4 rounded-lg border bg-gray-50 text-gray-700">
          No hay usuarios para esos filtros.
        </div>
      <?php else: ?>
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="text-left px-3 py-2">ID</th>
              <th class="text-left px-3 py-2">Nombre</th>
              <th class="text-left px-3 py-2">Correo</th>
              <th class="text-left px-3 py-2">Identificación</th>
              <th class="text-left px-3 py-2">Teléfono</th>
              <th class="text-left px-3 py-2">Estado</th>
              <th class="text-left px-3 py-2">Rol</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td class="px-3 py-2"><?= (int)$u['id_usuario'] ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($u['nombre']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($u['correo']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($u['identificacion'] ?? '') ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($u['telefono'] ?? '') ?></td>
                <td class="px-3 py-2">
                  <?php if (($u['estado'] ?? '') === 'activo'): ?>
                    <span class="px-2 py-1 rounded bg-green-100 text-green-800 border border-green-200">activo</span>
                  <?php else: ?>
                    <span class="px-2 py-1 rounded bg-red-100 text-red-800 border border-red-200">inactivo</span>
                  <?php endif; ?>
                </td>
                <td class="px-3 py-2"><?= htmlspecialchars($u['rol'] ?? '—') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
