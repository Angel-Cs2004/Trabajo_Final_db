<?php
// Variables que vienen del controller:
// $negocioInfo, $productosPorCategoria, $resumen, $disponibilidad
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Tienda</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    .header { margin-bottom: 14px; }
    .title { font-size: 18px; font-weight: bold; margin: 0; }
    .muted { color: #666; }
    .badge-open { display:inline-block; padding: 4px 10px; border-radius: 999px; background:#d1fae5; color:#065f46; font-weight: bold; }
    .badge-closed { display:inline-block; padding: 4px 10px; border-radius: 999px; background:#fee2e2; color:#991b1b; font-weight: bold; }
    .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th, td { border-top: 1px solid #e5e7eb; padding: 6px; text-align: left; }
    th { background: #f9fafb; }
    .grid { width: 100%; }
    .grid td { border: none; vertical-align: top; padding: 0; }
    .box { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; }
  </style>
</head>
<body>

  <div class="header">
    <p class="title">Detalle de Tienda</p>
    <p class="muted">Generado: <?= date('d/m/Y H:i') ?></p>
  </div>

  <div class="card">
    <table class="grid">
      <tr>
        <td style="width:75%">
          <p><b>Negocio:</b> <?= htmlspecialchars($negocioInfo['nombre'] ?? '-') ?></p>
          <p><b>Propietario:</b> <?= htmlspecialchars($negocioInfo['propietario'] ?? '-') ?></p>
          <p><b>Estado:</b> <?= htmlspecialchars($negocioInfo['estado'] ?? '-') ?></p>
          <p><b>Horario:</b>
            <?= htmlspecialchars(substr((string)($negocioInfo['hora_apertura'] ?? ''), 0, 5)) ?>
            -
            <?= htmlspecialchars(substr((string)($negocioInfo['hora_cierre'] ?? ''), 0, 5)) ?>
          </p>
          <?php if (!empty($negocioInfo['descripcion'])): ?>
            <p><b>Descripción:</b> <?= htmlspecialchars($negocioInfo['descripcion']) ?></p>
          <?php endif; ?>
        </td>
        <td style="width:25%; text-align:right">
          <?php if (($disponibilidad ?? '') === 'abierto'): ?>
            <span class="badge-open">ABIERTO</span>
          <?php else: ?>
            <span class="badge-closed">CERRADO</span>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>

  <?php if (!empty($resumen)): ?>
    <div class="card">
      <p><b>Resumen</b></p>
      <table>
        <tr>
          <th>Productos activos</th>
          <th>Precio mínimo</th>
          <th>Precio promedio</th>
          <th>Precio máximo</th>
        </tr>
        <tr>
          <td><?= (int)($resumen['total_productos'] ?? 0) ?></td>
          <td>S/ <?= htmlspecialchars((string)($resumen['precio_min'] ?? 0)) ?></td>
          <td>S/ <?= htmlspecialchars((string)($resumen['precio_promedio'] ?? 0)) ?></td>
          <td>S/ <?= htmlspecialchars((string)($resumen['precio_max'] ?? 0)) ?></td>
        </tr>
      </table>
    </div>
  <?php endif; ?>

  <div class="card">
    <p><b>Productos por categoría</b></p>

    <?php if (!empty($productosPorCategoria)): ?>
      <?php foreach ($productosPorCategoria as $cat => $items): ?>
        <div class="box" style="margin-bottom:10px;">
          <p style="margin:0 0 6px 0;"><b><?= htmlspecialchars((string)$cat) ?></b> (<?= count($items) ?>)</p>
          <table>
            <tr>
              <th>Producto</th>
              <th>Precio</th>
              <th>Estado</th>
            </tr>
            <?php foreach ($items as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
                <td>S/ <?= htmlspecialchars((string)($p['precio'] ?? 0)) ?></td>
                <td><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="muted">No hay productos activos para este negocio.</p>
    <?php endif; ?>
  </div>

</body>
</html>
