<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tablero de Categorías</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    h1 { margin: 0 0 6px 0; font-size: 18px; }
    .meta { font-size: 11px; color: #555; margin-bottom: 14px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
    th { background: #f2f2f2; text-align: left; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 11px; }
    .activo { background: #e7f7ee; color: #126b33; }
    .inactivo { background: #fdeaea; color: #9b1c1c; }
  </style>
</head>
<body>
  <h1>Tablero de Categorías</h1>
  <div class="meta">
    Total: <?= !empty($categoriasTablero) ? count($categoriasTablero) : 0 ?> |
    Generado: <?= date('d/m/Y H:i') ?>
  </div>

  <?php if (!empty($categoriasTablero)): ?>
    <table>
      <thead>
        <tr>
          <th style="width:70px;">ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th style="width:110px;">Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categoriasTablero as $c): ?>
          <?php $estado = $c['estado'] ?? 'inactivo'; ?>
          <tr>
            <td><?= (int)($c['id_categoria'] ?? 0) ?></td>
            <td><?= htmlspecialchars($c['nombre'] ?? '-') ?></td>
            <td><?= htmlspecialchars($c['descripcion'] ?? '-') ?></td>
            <td>
              <span class="badge <?= $estado === 'activo' ? 'activo' : 'inactivo' ?>">
                <?= htmlspecialchars($estado) ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No hay categorías registradas.</p>
  <?php endif; ?>
</body>
</html>
