<?php
$pageTitle = "Reporte por Negocio";
require __DIR__ . '/../../layouts/header.php';

$queryPdf = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfReporteNegocio',
    'id_negocio' => $idNegocio ?? 0
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Reporte por Negocio</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="reporteNegocio">

        <div class="md:col-span-2">
          <label class="text-sm font-medium">Negocio</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2" required>
            <option value="0">Selecciona un negocio</option>
            <?php foreach ($negocios as $n): ?>
              <option value="<?= (int)$n['id_negocio'] ?>" <?= ((int)$idNegocio === (int)$n['id_negocio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
          Ver
        </button>
      </form>
    </div>

    <div class="px-6 py-6">
      <?php if (!empty($productos)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left">Producto</th>
                <th class="px-4 py-2 text-left">Categoría</th>
                <th class="px-4 py-2 text-left">Precio</th>
                <th class="px-4 py-2 text-left">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos as $p): ?>
                <tr class="border-t">
                  <td class="px-4 py-2"><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                  <td class="px-4 py-2">S/ <?= htmlspecialchars((string)$p['precio']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-500 text-sm">Selecciona un negocio para mostrar sus productos.</p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
