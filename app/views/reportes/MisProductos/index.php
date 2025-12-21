<?php
$pageTitle = "Mis Productos";
require __DIR__ . '/../../layouts/header.php';

$runMisProductos = isset($_GET['run_mis_productos']) ? (int)$_GET['run_mis_productos'] : 0;

$queryPdf = http_build_query([
  'c' => 'reporte',
  'a' => 'pdfMisProductos',
  'id_negocio' => $idNegocio ?? 0,
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-6">

  <div class="bg-white rounded-lg shadow">

    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Mis Productos (todas mis tiendas)</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium
                <?= ($runMisProductos === 1) ? '' : 'opacity-50 pointer-events-none' ?>">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="misProductos">
        <input type="hidden" name="run_mis_productos" value="1">

        <div class="md:col-span-2">
          <label class="text-sm font-medium">Tienda (opcional)</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2">
            <option value="0" <?= ((int)($idNegocio ?? 0) === 0) ? 'selected' : '' ?>>Todas mis tiendas</option>
            <?php foreach (($negocios ?? []) as $n): ?>
              <option value="<?= (int)$n['id_negocio'] ?>"
                <?= ((int)($idNegocio ?? 0) === (int)$n['id_negocio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['nombre'] ?? '-') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex justify-end gap-2">
          <a href="index.php?c=reporte&a=misProductos"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Ver
          </button>
        </div>
      </form>
    </div>

    <?php if ($runMisProductos === 1): ?>
      <div class="px-6 py-6">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Productos</h2>
          <span class="text-xs text-gray-500">Total: <?= !empty($productos) ? count($productos) : 0 ?></span>
        </div>

        <?php if (!empty($productos)): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left">Negocio</th>
                  <th class="px-4 py-2 text-left">Categoría</th>
                  <th class="px-4 py-2 text-left">Producto</th>
                  <th class="px-4 py-2 text-left">Precio</th>
                  <th class="px-4 py-2 text-left">Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($productos as $p): ?>
                  <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2"><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['producto'] ?? '-') ?></td>
                    <td class="px-4 py-2">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
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
