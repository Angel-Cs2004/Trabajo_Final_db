<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Mis Negocios</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1 { font-size: 18px; margin: 0 0 10px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
    th { background: #f2f2f2; }
    .meta { margin-bottom: 10px; color: #444; }
  </style>
</head>
<body>
  <h1>Mis Negocios</h1>
  <div class="meta">
    Estado: <?= htmlspecialchars($estado ?? 'todos') ?> |
    Búsqueda: <?= htmlspecialchars($busqueda ?? '') ?>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Negocio</th><th>Descripción</th><th>Estado</th><th>Horario</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($misNegocios)): ?>
        <?php foreach ($misNegocios as $n): ?>
          <tr>
            <td><?= (int)($n['id_negocio'] ?? 0) ?></td>
            <td><?= htmlspecialchars($n['nombre'] ?? '-') ?></td>
            <td><?= htmlspecialchars($n['descripcion'] ?? '-') ?></td>
            <td><?= htmlspecialchars($n['estado'] ?? '-') ?></td>
            <td><?= htmlspecialchars(($n['hora_apertura'] ?? '--') . ' - ' . ($n['hora_cierre'] ?? '--')) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5">Sin resultados</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
