<?php
$pageTitle = "Reporte de Usuarios";
require __DIR__ . '/../../layouts/header.php';

$queryPdf = http_build_query([
  'c' => 'reporte',
  'a' => 'pdfUsuarios',
  'id_rol' => $idRol ?? 0,
  'estado' => $estado ?? 'todos',
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Reporte de Usuarios</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="usuarios">

        <div>
          <label class="text-sm font-medium">Rol</label>
          <select name="id_rol" class="w-full border rounded px-3 py-2">
            <option value="0">Todos</option>
            <?php foreach ($roles as $r): ?>
              <option value="<?= (int)$r['id_rol'] ?>" <?= ((int)$idRol === (int)$r['id_rol']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="text-sm font-medium">Estado</label>
          <select name="estado" class="w-full border rounded px-3 py-2">
            <?php foreach (['todos','activo','inactivo'] as $e): ?>
              <option value="<?= $e ?>" <?= ($estado === $e) ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
          Filtrar
        </button>
      </form>
    </div>

    <div class="px-6 py-6">
      <?php if (!empty($usuarios)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Correo</th>
                <th class="px-4 py-2 text-left">Identificación</th>
                <th class="px-4 py-2 text-left">Teléfono</th>
                <th class="px-4 py-2 text-left">Rol</th>
                <th class="px-4 py-2 text-left">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuarios as $u): ?>
                <tr class="border-t">
                  <td class="px-4 py-2"><?= htmlspecialchars($u['nombre']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($u['correo']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($u['identificacion'] ?? '-') ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($u['telefono'] ?? '-') ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($u['rol'] ?? '-') ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($u['estado'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-500 text-sm">No hay usuarios con esos filtros.</p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
