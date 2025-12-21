<?php
$pageTitle = "Mis Negocios";
require __DIR__ . '/../../layouts/header.php';

$runMisNegocios = isset($_GET['run_mis_negocios']) ? (int)$_GET['run_mis_negocios'] : 0;

$queryPdf = http_build_query([
  'c' => 'reporte',
  'a' => 'pdfMisNegocios',
  'estado' => $estado ?? 'todos',
  'busqueda' => $busqueda ?? '',
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-6">

  <div class="bg-white rounded-lg shadow">

    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Mis Negocios</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium
                <?= ($runMisNegocios === 1) ? '' : 'opacity-50 pointer-events-none' ?>">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="misNegocios">
        <input type="hidden" name="run_mis_negocios" value="1">

        <div>
          <label class="text-sm font-medium">Estado</label>
          <select name="estado" class="w-full border rounded px-3 py-2">
            <option value="todos"   <?= (($estado ?? 'todos') === 'todos') ? 'selected' : '' ?>>Todos</option>
            <option value="activo"  <?= (($estado ?? 'todos') === 'activo') ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo"<?= (($estado ?? 'todos') === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
          </select>
        </div>

        <div>
          <label class="text-sm font-medium">Buscar por nombre</label>
          <input type="text" name="busqueda"
                 value="<?= htmlspecialchars($busqueda ?? '') ?>"
                 class="w-full border rounded px-3 py-2"
                 placeholder="Ej: Café Angelo">
        </div>

        <div class="flex justify-end gap-2">
          <a href="index.php?c=reporte&a=misNegocios"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Ver
          </button>
        </div>
      </form>
    </div>

    <?php if ($runMisNegocios === 1): ?>
      <div class="px-6 py-6">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Listado</h2>
          <span class="text-xs text-gray-500">Total: <?= !empty($misNegocios) ? count($misNegocios) : 0 ?></span>
        </div>

        <?php if (!empty($misNegocios)): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left">ID</th>
                  <th class="px-4 py-2 text-left">Negocio</th>
                  <th class="px-4 py-2 text-left">Descripción</th>
                  <th class="px-4 py-2 text-left">Estado</th>
                  <th class="px-4 py-2 text-left">Horario</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($misNegocios as $n): ?>
                  <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2"><?= (int)($n['id_negocio'] ?? 0) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($n['nombre'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($n['descripcion'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($n['estado'] ?? '-') ?></td>
                    <td class="px-4 py-2">
                      <?= htmlspecialchars(($n['hora_apertura'] ?? '--') . ' - ' . ($n['hora_cierre'] ?? '--')) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-500 text-sm">No hay resultados para mostrar.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
