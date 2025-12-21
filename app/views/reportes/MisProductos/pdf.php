<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Mis Productos</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1 { font-size: 18px; margin: 0 0 10px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
    th { background: #f2f2f2; }
  </style>
</head>
<body>
  <h1>Mis Productos</h1>

  <table>
    <thead>
      <tr>
        <th>Negocio</th><th>Categoría</th><th>Producto</th><th>Precio</th><th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['negocio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['producto'] ?? '-') ?></td>
            <td>S/ <?= htmlspecialchars((string)($p['precio'] ?? '0')) ?></td>
            <td><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5">Sin resultados</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
