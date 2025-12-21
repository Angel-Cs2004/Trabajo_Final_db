<pre><?php var_dump($propietarios); ?></pre>

<?php
$pageTitle = "Detalle de Tienda";
require __DIR__ . '/../../layouts/header.php';

// Botón PDF (solo si hay negocio elegido)
$queryPdf = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfDetalleTienda',
    'id_propietario' => $idPropietario ?? 0,
    'id_negocio'     => $idNegocio ?? 0,
]);

$run = isset($_GET['run_detalle']) ? (int)$_GET['run_detalle'] : 0;
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto space-y-8">

  <div class="bg-white rounded-lg shadow">

    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Detalle de Tienda</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium <?= ((int)($idNegocio ?? 0) <= 0) ? 'opacity-50 pointer-events-none' : '' ?>">
        Descargar PDF
      </a>
    </div>

    <!-- Filtros -->
    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="detalleTienda">
        <!-- ✅ para que recién muestre resultados -->
        <input type="hidden" name="run_detalle" value="1">

        <div>
          <label class="text-sm font-medium">Propietario</label>
          <select name="id_propietario" class="w-full border rounded px-3 py-2" required>
            <option value="0">Selecciona un propietario</option>
            <?php foreach (($propietarios ?? []) as $p): ?>
              <option value="<?= (int)$p['id_usuario'] ?>"
                <?= ((int)($idPropietario ?? 0) === (int)$p['id_usuario']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <p class="text-xs text-gray-500 mt-1">Primero elige propietario para cargar sus negocios.</p>
        </div>

        <div>
          <label class="text-sm font-medium">Negocio</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2" <?= ((int)($idPropietario ?? 0) <= 0) ? 'disabled' : '' ?>>
            <option value="0">Selecciona un negocio</option>
            <?php foreach (($negocios ?? []) as $n): ?>
              <option value="<?= (int)$n['id_negocio'] ?>"
                <?= ((int)($idNegocio ?? 0) === (int)$n['id_negocio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['negocio'] ?? $n['nombre'] ?? '-') ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ((int)($idPropietario ?? 0) <= 0): ?>
            <p class="text-xs text-gray-500 mt-1">Selecciona un propietario para habilitar.</p>
          <?php endif; ?>
        </div>

        <div class="flex justify-end gap-2">
          <a href="index.php?c=reporte&a=detalleTienda"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Ver
          </button>
        </div>
      </form>
    </div>

    <!-- Resultados -->
    <?php if ($run === 1): ?>
      <div class="px-6 py-6 space-y-6">

        <?php if (!empty($negocioInfo)): ?>
          <!-- Info negocio -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 border rounded-lg p-4">
              <div class="text-xs text-gray-500">Negocio</div>
              <div class="font-semibold text-gray-800"><?= htmlspecialchars($negocioInfo['nombre'] ?? '-') ?></div>
              <div class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($negocioInfo['descripcion'] ?? '-') ?></div>
            </div>

            <div class="bg-gray-50 border rounded-lg p-4">
              <div class="text-xs text-gray-500">Propietario</div>
              <div class="font-semibold text-gray-800"><?= htmlspecialchars($negocioInfo['propietario'] ?? '-') ?></div>
              <div class="text-sm text-gray-600 mt-1">
                Estado tienda: <span class="font-medium"><?= htmlspecialchars($negocioInfo['estado'] ?? '-') ?></span>
              </div>
            </div>

            <div class="bg-gray-50 border rounded-lg p-4">
              <div class="text-xs text-gray-500">Disponibilidad</div>
              <div class="font-semibold text-gray-800">
                <?= htmlspecialchars($disponibilidad ?? '-') ?>
              </div>
              <div class="text-sm text-gray-600 mt-1">
                <?= htmlspecialchars($negocioInfo['hora_apertura'] ?? '--:--') ?> - <?= htmlspecialchars($negocioInfo['hora_cierre'] ?? '--:--') ?>
              </div>
            </div>
          </div>

          <!-- Resumen precios -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border rounded-lg p-4">
              <div class="text-xs text-gray-500">Total productos</div>
              <div class="text-lg font-semibold"><?= (int)($resumen['total_productos'] ?? 0) ?></div>
            </div>
            <div class="bg-white border rounded-lg p-4">
              <div class="text-xs text-gray-500">Precio mín.</div>
              <div class="text-lg font-semibold">S/ <?= htmlspecialchars((string)($resumen['precio_min'] ?? 0)) ?></div>
            </div>
            <div class="bg-white border rounded-lg p-4">
              <div class="text-xs text-gray-500">Precio máx.</div>
              <div class="text-lg font-semibold">S/ <?= htmlspecialchars((string)($resumen['precio_max'] ?? 0)) ?></div>
            </div>
            <div class="bg-white border rounded-lg p-4">
              <div class="text-xs text-gray-500">Promedio</div>
              <div class="text-lg font-semibold">S/ <?= htmlspecialchars((string)($resumen['precio_promedio'] ?? 0)) ?></div>
            </div>
          </div>

          <!-- Cuadritos por categoría -->
          <?php if (!empty($productosPorCategoria)): ?>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Productos por Categoría</h2>
                <span class="text-xs text-gray-500">Categorías: <?= count($productosPorCategoria) ?></span>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($productosPorCategoria as $categoria => $items): ?>
                  <div class="border rounded-lg">
                    <div class="px-4 py-3 border-b bg-gray-50 flex justify-between items-center">
                      <div class="font-semibold text-gray-800"><?= htmlspecialchars($categoria) ?></div>
                      <div class="text-xs text-gray-500">Items: <?= count($items) ?></div>
                    </div>

                    <div class="p-4">
                      <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                          <thead class="bg-white">
                            <tr class="text-gray-600">
                              <th class="py-2 text-left">Producto</th>
                              <th class="py-2 text-left">Precio</th>
                              <th class="py-2 text-left">Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($items as $p): ?>
                              <tr class="border-t">
                                <td class="py-2"><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                                <td class="py-2">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                                <td class="py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php else: ?>
            <p class="text-gray-500 text-sm">No hay productos para mostrar.</p>
          <?php endif; ?>

        <?php else: ?>
          <p class="text-gray-500 text-sm">
            Selecciona un propietario y un negocio para mostrar el detalle.
          </p>
        <?php endif; ?>

      </div>
    <?php endif; ?>

  </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
