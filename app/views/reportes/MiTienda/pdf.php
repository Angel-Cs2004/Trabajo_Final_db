<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
  body{font-family:DejaVu Sans, sans-serif; font-size:12px;}
  .header{border-bottom:2px solid #1f7a3a; padding-bottom:8px; margin-bottom:12px;}
  h1{font-size:18px; margin:0;}
  .meta{font-size:11px; color:#555;}
  table{width:100%; border-collapse:collapse; margin-top:8px;}
  th,td{border:1px solid #ddd; padding:6px; vertical-align:top;}
  th{background:#f3f4f6;}
</style>
</head>
<body>

  <div class="header">
    <h1>Reporte: Mi Tienda (Ficha completa)</h1>
    <div class="meta">
      Generado: <?= date('Y-m-d H:i') ?> |
      Usuario: <?= htmlspecialchars($_SESSION['nombre'] ?? '-') ?>
    </div>
  </div>

  <!-- =========================
       DATOS DE LA TIENDA
  ========================== -->
  <table>
    <tbody>
      <tr>
        <th style="width:180px;">Negocio</th>
        <td><?= htmlspecialchars($negocioInfo['nombre'] ?? '-') ?></td>
      </tr>
      <tr>
        <th>Propietario</th>
        <td><?= htmlspecialchars($negocioInfo['propietario'] ?? '-') ?></td>
      </tr>
      <tr>
        <th>Estado</th>
        <td><?= htmlspecialchars($negocioInfo['estado'] ?? '-') ?></td>
      </tr>
      <tr>
        <th>Disponibilidad</th>
        <td><?= htmlspecialchars($disponibilidad ?? '-') ?></td>
      </tr>
      <tr>
        <th>Horario</th>
        <td>
          <?= htmlspecialchars(($negocioInfo['hora_apertura'] ?? '--') . ' - ' . ($negocioInfo['hora_cierre'] ?? '--')) ?>
        </td>
      </tr>
      <tr>
        <th>Descripción</th>
        <td><?= htmlspecialchars($negocioInfo['descripcion'] ?? '-') ?></td>
      </tr>
    </tbody>
  </table>

  <!-- =========================
       RESUMEN
  ========================== -->
  <?php if (!empty($resumen)): ?>
    <table>
      <thead>
        <tr>
          <th>Total productos</th>
          <th>Precio mínimo</th>
          <th>Precio promedio</th>
          <th>Precio máximo</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= (int)($resumen['total_productos'] ?? 0) ?></td>
          <td>S/ <?= htmlspecialchars((string)($resumen['precio_min'] ?? 0)) ?></td>
          <td>S/ <?= htmlspecialchars((string)($resumen['precio_promedio'] ?? 0)) ?></td>
          <td>S/ <?= htmlspecialchars((string)($resumen['precio_max'] ?? 0)) ?></td>
        </tr>
      </tbody>
    </table>
  <?php else: ?>
    <div class="meta">Sin resumen.</div>
  <?php endif; ?>

  <!-- =========================
       PRODUCTOS POR CATEGORÍA
  ========================== -->
  <?php if (!empty($productosPorCategoria)): ?>
    <?php foreach ($productosPorCategoria as $categoria => $items): ?>

      <div class="meta" style="margin-top:12px;">
        <?= htmlspecialchars((string)$categoria) ?> (<?= count($items) ?>)
      </div>

      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th style="width:110px;">Precio</th>
            <th style="width:120px;">Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['producto'] ?? '-') ?></td>
              <td>S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
              <td><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php endforeach; ?>
  <?php else: ?>
    <div class="meta">Sin productos registrados.</div>
  <?php endif; ?>

</body>
</html>
