<?php
$pageTitle = "Mi Tienda";
require __DIR__ . '/../../layouts/header.php';

$runMiTienda = isset($_GET['run_mi_tienda']) ? (int)$_GET['run_mi_tienda'] : 0;

$queryPdf = http_build_query([
  'c' => 'reporte',
  'a' => 'pdfMiTienda',
  'id_negocio' => $idNegocio ?? 0
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-6">

  <div class="bg-white rounded-lg shadow">

    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Reporte: Mi Tienda (Ficha completa)</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium
                <?= ($runMiTienda === 1 && (int)($idNegocio ?? 0) > 0 && !empty($negocioInfo)) ? '' : 'opacity-50 pointer-events-none' ?>">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="miTienda">
        <input type="hidden" name="run_mi_tienda" value="1">

        <div class="md:col-span-2">
          <label class="text-sm font-medium">Selecciona una de mis tiendas</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2">
            <option value="0">Selecciona</option>
            <?php foreach (($negocios ?? []) as $n): ?>
              <option value="<?= (int)($n['id_negocio'] ?? 0) ?>"
                <?= ((int)($idNegocio ?? 0) === (int)($n['id_negocio'] ?? 0)) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['nombre'] ?? '-') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex justify-end gap-2">
          <a href="index.php?c=reporte&a=miTienda"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Ver
          </button>
        </div>
      </form>
    </div>

    <?php if ($runMiTienda === 1): ?>
      <div class="px-6 py-6 space-y-6">

        <?php if (!empty($negocioInfo)): ?>

          <!-- Datos de la tienda -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="border rounded p-4">
              <div class="text-xs text-gray-500">Negocio</div>
              <div class="font-semibold"><?= htmlspecialchars($negocioInfo['nombre'] ?? '-') ?></div>
            </div>
            <div class="border rounded p-4">
              <div class="text-xs text-gray-500">Estado</div>
              <div class="font-semibold"><?= htmlspecialchars($negocioInfo['estado'] ?? '-') ?></div>
            </div>
            <div class="border rounded p-4">
              <div class="text-xs text-gray-500">Disponibilidad</div>
              <div class="font-semibold"><?= htmlspecialchars($disponibilidad ?? '-') ?></div>
            </div>
            <div class="border rounded p-4">
              <div class="text-xs text-gray-500">Horario</div>
              <div class="font-semibold">
                <?= htmlspecialchars(($negocioInfo['hora_apertura'] ?? '--') . ' - ' . ($negocioInfo['hora_cierre'] ?? '--')) ?>
              </div>
            </div>
          </div>

          <?php if (!empty($negocioInfo['descripcion'])): ?>
            <div class="border rounded p-4">
              <div class="text-xs text-gray-500">Descripción</div>
              <div class="font-medium"><?= htmlspecialchars($negocioInfo['descripcion']) ?></div>
            </div>
          <?php endif; ?>

          <!-- Resumen -->
          <?php if (!empty($resumen)): ?>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div class="border rounded p-4">
                <div class="text-xs text-gray-500">Total productos</div>
                <div class="font-semibold"><?= (int)($resumen['total_productos'] ?? 0) ?></div>
              </div>
              <div class="border rounded p-4">
                <div class="text-xs text-gray-500">Precio mínimo</div>
                <div class="font-semibold">S/ <?= htmlspecialchars((string)($resumen['precio_min'] ?? 0)) ?></div>
              </div>
              <div class="border rounded p-4">
                <div class="text-xs text-gray-500">Precio máximo</div>
                <div class="font-semibold">S/ <?= htmlspecialchars((string)($resumen['precio_max'] ?? 0)) ?></div>
              </div>
              <div class="border rounded p-4">
                <div class="text-xs text-gray-500">Precio promedio</div>
                <div class="font-semibold">S/ <?= htmlspecialchars((string)($resumen['precio_promedio'] ?? 0)) ?></div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Productos agrupados por categoría -->
          <?php if (!empty($productosPorCategoria)): ?>
            <?php foreach ($productosPorCategoria as $cat => $items): ?>
              <div class="border rounded-lg">
                <div class="px-4 py-2 border-b bg-gray-50 font-semibold">
                  <?= htmlspecialchars($cat) ?> (<?= count($items) ?>)
                </div>
                <div class="p-4 overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="text-left text-gray-600">
                        <th class="py-1">Producto</th>
                        <th class="py-1">Precio</th>
                        <th class="py-1">Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($items as $p): ?>
                        <tr class="border-t">
                          <td class="py-1"><?= htmlspecialchars($p['producto'] ?? '-') ?></td>
                          <td class="py-1">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                          <td class="py-1"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-500 text-sm">Esta tienda no tiene productos.</p>
          <?php endif; ?>

        <?php else: ?>
          <p class="text-gray-500 text-sm">Selecciona una tienda válida (solo tus tiendas).</p>
        <?php endif; ?>

      </div>
    <?php endif; ?>

  </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
