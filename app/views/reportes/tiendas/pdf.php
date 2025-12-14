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
  th,td{border:1px solid #ddd; padding:6px;}
  th{background:#f3f4f6;}
</style>
</head>
<body>
  <div class="header">
    <h1>Resumen de Tiendas</h1>
    <div class="meta">Generado: <?= date('Y-m-d H:i') ?> | Usuario: <?= htmlspecialchars($_SESSION['nombre'] ?? '-') ?></div>
  </div>

  <?php if (!empty($resumen)): ?>
    <table>
      <thead>
        <tr>
          <th>Tienda</th><th>Propietario</th><th>Estado</th><th>Horario</th>
          <th>Prod. activos</th><th>Cat. distintas</th><th>Prom.</th><th>Min</th><th>Max</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($resumen as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['negocio']) ?></td>
            <td><?= htmlspecialchars($r['propietario']) ?></td>
            <td><?= htmlspecialchars($r['estado']) ?></td>
            <td><?= htmlspecialchars($r['hora_apertura'] . ' - ' . $r['hora_cierre']) ?></td>
            <td><?= (int)$r['total_productos_activos'] ?></td>
            <td><?= (int)$r['categorias_distintas'] ?></td>
            <td>S/ <?= htmlspecialchars((string)$r['precio_promedio']) ?></td>
            <td>S/ <?= htmlspecialchars((string)$r['precio_min']) ?></td>
            <td>S/ <?= htmlspecialchars((string)$r['precio_max']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="meta">Sin resultados.</div>
  <?php endif; ?>
</body>
</html>
