<?php
$pageTitle = "Reporte Roles y Permisos";
require __DIR__ . '/../../layouts/header.php';

$queryPdf = http_build_query([
  'c' => 'reporte',
  'a' => 'pdfRolesPermisos',
  'id_rol' => $idRol ?? 0,
  'tag' => $tag ?? 'todos',
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Roles y Permisos</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="rolesPermisos">

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
          <label class="text-sm font-medium">Tag (módulo)</label>
          <select name="tag" class="w-full border rounded px-3 py-2">
            <option value="todos">Todos</option>
            <?php foreach ($tags as $t): ?>
              <option value="<?= htmlspecialchars($t['modulos']) ?>" <?= ($tag === $t['modulos']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['modulos']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
          Filtrar
        </button>
      </form>
    </div>

    <div class="px-6 py-6">
      <?php if (!empty($data)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left">Rol</th>
                <th class="px-4 py-2 text-left">Estado Rol</th>
                <th class="px-4 py-2 text-left">Tag</th>
                <th class="px-4 py-2 text-left">Permiso</th>
                <th class="px-4 py-2 text-left">CRUD</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $row): ?>
                <tr class="border-t">
                  <td class="px-4 py-2"><?= htmlspecialchars($row['rol']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['estado_rol']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['tag_modulo']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['permiso']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['crud']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-500 text-sm">No hay permisos para esos filtros.</p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
