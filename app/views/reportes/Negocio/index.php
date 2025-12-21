<?php
$pageTitle = "Reportes - Negocio";
require __DIR__ . '/../../layouts/header.php';

$queryPdf = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfReporteNegocioMio',
    'id_negocio' => $idNegocio ?? 0,
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-6">

  <div class="bg-white rounded-lg shadow">

    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">
        Reportes de Mis Negocios
      </h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium
                <?= ((int)($idNegocio ?? 0) > 0) ? '' : 'opacity-50 pointer-events-none' ?>">
        Descargar PDF
      </a>
    </div>

    <!-- ===================================================== -->
    <!-- MENU DE REPORTES (SOLO MIS REPORTES) -->
    <!-- ===================================================== -->
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3">



        <a href="index.php?c=reporte&a=misProductos"
           class="border rounded-lg p-4 hover:bg-gray-50 transition">
          <div class="text-sm font-semibold">Mis Productos</div>
          <div class="text-xs text-gray-500 mt-1">
            Productos de todas mis tiendas + PDF
          </div>
        </a>

        <a href="index.php?c=reporte&a=miTienda"
           class="border rounded-lg p-4 hover:bg-gray-50 transition">
          <div class="text-sm font-semibold">Mi Tienda</div>
          <div class="text-xs text-gray-500 mt-1">
            Ficha completa de una tienda + PDF
          </div>
        </a>

        <div class="border rounded-lg p-4 bg-green-50 border-green-300">
          <div class="text-sm font-semibold text-green-800">
            Productos por Negocio
          </div>
          <div class="text-xs text-green-700 mt-1">
            Reporte actual
          </div>
        </div>

      </div>
    </div>

    <!-- ===================================================== -->
    <!-- FILTRO -->
    <!-- ===================================================== -->
    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="reporteNegocioMio">

        <div>
          <label class="text-sm font-medium">Selecciona mi negocio</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2" required>
            <option value="0">Selecciona</option>
            <?php foreach (($negocios ?? []) as $n): ?>
              <option value="<?= (int)$n['id_negocio'] ?>"
                <?= ((int)($idNegocio ?? 0) === (int)$n['id_negocio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex justify-end gap-2">
          <a href="index.php?c=reporte&a=reporteNegocioMio"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>

          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Ver
          </button>
        </div>
      </form>
    </div>

    <!-- ===================================================== -->
    <!-- RESULTADOS -->
    <!-- ===================================================== -->
    <div class="px-6 py-6">

      <?php if (!empty($productos)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left">Categoría</th>
                <th class="px-4 py-2 text-left">Producto</th>
                <th class="px-4 py-2 text-left">Precio</th>
                <th class="px-4 py-2 text-left">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos as $p): ?>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-2"><?= htmlspecialchars($p['categoria']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($p['producto']) ?></td>
                  <td class="px-4 py-2">S/ <?= number_format($p['precio'], 2) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($p['estado']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php elseif ((int)($idNegocio ?? 0) > 0): ?>
        <p class="text-gray-500 text-sm">
          Este negocio no tiene productos registrados.
        </p>

      <?php else: ?>
        <p class="text-gray-500 text-sm">
          Selecciona uno de tus negocios para ver sus productos.
        </p>
      <?php endif; ?>

    </div>

  </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
