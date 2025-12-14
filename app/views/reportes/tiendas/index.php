<?php
$pageTitle = "Resumen de Tiendas";
require __DIR__ . '/../../layouts/header.php';
?>

<main class="flex-1 px-10 pt-14 pb-14 overflow-auto">
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Resumen de Tiendas</h1>

      <a href="index.php?c=reporte&a=pdfResumenTiendas"
         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
        Descargar PDF
      </a>
    </div>

    <div class="px-6 py-6">
      <?php if (!empty($resumen)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left">Tienda</th>
                <th class="px-4 py-2 text-left">Propietario</th>
                <th class="px-4 py-2 text-left">Estado</th>
                <th class="px-4 py-2 text-left">Horario</th>
                <th class="px-4 py-2 text-left">Productos activos</th>
                <th class="px-4 py-2 text-left">Cat. distintas</th>
                <th class="px-4 py-2 text-left">Promedio</th>
                <th class="px-4 py-2 text-left">Min</th>
                <th class="px-4 py-2 text-left">Max</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($resumen as $r): ?>
                <tr class="border-t">
                  <td class="px-4 py-2"><?= htmlspecialchars($r['negocio']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($r['propietario']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($r['estado']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($r['hora_apertura'] . " - " . $r['hora_cierre']) ?></td>
                  <td class="px-4 py-2"><?= (int)$r['total_productos_activos'] ?></td>
                  <td class="px-4 py-2"><?= (int)$r['categorias_distintas'] ?></td>
                  <td class="px-4 py-2">S/ <?= htmlspecialchars((string)$r['precio_promedio']) ?></td>
                  <td class="px-4 py-2">S/ <?= htmlspecialchars((string)$r['precio_min']) ?></td>
                  <td class="px-4 py-2">S/ <?= htmlspecialchars((string)$r['precio_max']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-500 text-sm">No hay tiendas registradas.</p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
