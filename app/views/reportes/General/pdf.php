<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
  body{font-family:DejaVu Sans, sans-serif; font-size:12px; color:#111;}
  .header{border-bottom:2px solid #1f7a3a; padding-bottom:8px; margin-bottom:12px;}
  h1{font-size:18px; margin:0;}
  .meta{font-size:11px; color:#555;}
  table{width:100%; border-collapse:collapse; margin-top:8px;}
  th,td{border:1px solid #ddd; padding:6px;}
  th{background:#f3f4f6;}
  .section{margin-top:14px;}
  .footer{margin-top:14px; font-size:10px; color:#666; text-align:center;}
</style>
</head>
<body>
  <div class="header">
    <h1>Reporte General</h1>
    <div class="meta">
      Generado: <?= date('Y-m-d H:i') ?> | Usuario: <?= htmlspecialchars($_SESSION['nombre'] ?? '-') ?>
    </div>
  </div>

  <div class="meta">
    Filtros: Categoría ID <?= (int)$idCategoria ?> | Precio: <?= (float)$precioMin ?> - <?= (float)$precioMax ?> | Negocio ID <?= (int)$idNegocio ?>
  </div>

  <div class="section">
    <h3>Productos por Categoría</h3>
    <?php if (!empty($porCategoria)): ?>
      <table>
        <thead><tr><th>Producto</th><th>Precio</th><th>Negocio</th><th>Estado</th></tr></thead>
        <tbody>
        <?php foreach($porCategoria as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
            <td>S/ <?= htmlspecialchars((string)$p['precio']) ?></td>
            <td><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="meta">Sin resultados.</div>
    <?php endif; ?>
  </div>

  <div class="section">
    <h3>Productos por Rango de Precio</h3>
    <?php if (!empty($porRango)): ?>
      <table>
        <thead><tr><th>Producto</th><th>Precio</th><th>Categoría</th><th>Negocio</th><th>Estado</th></tr></thead>
        <tbody>
        <?php foreach($porRango as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['producto'] ?? $p['nombre'] ?? '-') ?></td>
            <td>S/ <?= htmlspecialchars((string)$p['precio']) ?></td>
            <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="meta">Sin resultados.</div>
    <?php endif; ?>
  </div>

  <div class="footer">Sistema de Negocios 2025 - Reporte</div>
</body>
</html>
