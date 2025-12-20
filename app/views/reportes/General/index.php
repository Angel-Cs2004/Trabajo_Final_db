<?php
$pageTitle = "Reporte General";
require __DIR__ . '/../../layouts/header.php';

$queryPdf = http_build_query([
    'c' => 'reporte',
    'a' => 'pdfReporteGeneral',
    'id_categoria' => $idCategoria ?? 0,
    'precio_min'   => $precioMin ?? 0,
    'precio_max'   => $precioMax ?? 0,
    'id_negocio'   => $idNegocio ?? 0,
]);
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
  <div class="bg-white rounded-lg shadow">

    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Reporte General</h1>

      <a href="index.php?<?= $queryPdf ?>"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <!-- Filtros -->
    <div class="px-6 py-4 border-b border-gray-200">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="c" value="reporte">
        <input type="hidden" name="a" value="reporteGeneral">

        <div>
          <label class="text-sm font-medium">Categoría</label>
          <select name="id_categoria" class="w-full border rounded px-3 py-2">
            <option value="0" <?= ((int)($idCategoria ?? 0) === 0) ? 'selected' : '' ?>>Todas (muestra todo)</option>
            <?php foreach (($categorias ?? []) as $c): ?>
              <option value="<?= (int)$c['id_categoria'] ?>"
                <?= ((int)($idCategoria ?? 0) === (int)$c['id_categoria']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="text-sm font-medium">Precio mínimo</label>
          <input type="number" step="0.01" name="precio_min"
                 value="<?= htmlspecialchars((string)($precioMin ?? 0)) ?>"
                 class="w-full border rounded px-3 py-2"
                 placeholder="0">
        </div>

        <div>
          <label class="text-sm font-medium">Precio máximo</label>
          <input type="number" step="0.01" name="precio_max"
                 value="<?= htmlspecialchars((string)($precioMax ?? 0)) ?>"
                 class="w-full border rounded px-3 py-2"
                 placeholder="0 (muestra todo)">
        </div>

        <div>
          <label class="text-sm font-medium">Negocio (para rango de precio)</label>
          <select name="id_negocio" class="w-full border rounded px-3 py-2">
            <option value="0" <?= ((int)($idNegocio ?? 0) === 0) ? 'selected' : '' ?>>Todos los negocios</option>
            <?php foreach (($negocios ?? []) as $n): ?>
              <option value="<?= (int)$n['id_negocio'] ?>"
                <?= ((int)($idNegocio ?? 0) === (int)$n['id_negocio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($n['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="md:col-span-4 flex justify-end gap-2 pt-2">
          <a href="index.php?c=reporte&a=reporteGeneral"
             class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Limpiar
          </a>
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Aplicar filtros
          </button>
        </div>
      </form>
    </div>

    <!-- Resultados -->
    <div class="px-6 py-6 space-y-8">

      <!-- Productos por Categoría -->
      <section>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Productos por Categoría</h2>
          <span class="text-xs text-gray-500">
            <?= ((int)($idCategoria ?? 0) === 0) ? 'Mostrando: todas las categorías' : 'Mostrando: categoría seleccionada' ?>
          </span>
        </div>

        <?php if (!empty($porCategoria)): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left">Categoría</th>
                  <th class="px-4 py-2 text-left">Producto</th>
                  <th class="px-4 py-2 text-left">Precio</th>
                  <th class="px-4 py-2 text-left">Negocio</th>
                  <th class="px-4 py-2 text-left">Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($porCategoria as $p): ?>
                  <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2"><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                    <td class="px-4 py-2">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-500 text-sm">
            No hay resultados para mostrar.
          </p>
        <?php endif; ?>
      </section>

      <!-- Productos por Rango de Precio -->
      <section>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Productos por Rango de Precio</h2>
          <span class="text-xs text-gray-500">
            Rango: S/ <?= htmlspecialchars((string)($precioMin ?? 0)) ?> – S/ <?= htmlspecialchars((string)($precioMax ?? 0)) ?>
            <?= ((int)($idNegocio ?? 0) === 0) ? '(todos los negocios)' : '(negocio seleccionado)' ?>
          </span>
        </div>

        <?php if (!empty($porRango)): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left">Producto</th>
                  <th class="px-4 py-2 text-left">Precio</th>
                  <th class="px-4 py-2 text-left">Categoría</th>
                  <th class="px-4 py-2 text-left">Negocio</th>
                  <th class="px-4 py-2 text-left">Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($porRango as $p): ?>
                  <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2"><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                    <td class="px-4 py-2">S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-500 text-sm">
            No hay resultados para mostrar.
          </p>
        <?php endif; ?>
      </section>

    </div>
  </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
