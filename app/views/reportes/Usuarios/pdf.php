<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte de Usuarios</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    h1 { font-size: 18px; margin: 0 0 8px; }
    .meta { margin: 0 0 12px; font-size: 11px; color: #444; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
    th { background: #f2f2f2; text-align: left; }
    .badge-ok { background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 10px; }
    .badge-bad { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 10px; }
  </style>
</head>
<body>

  <h1>Reporte de Usuarios</h1>

  <div class="meta">
    <div><strong>Filtro rol:</strong> <?= (int)$idRol === 0 ? 'Todos' : (function() use ($roles, $idRol) {
      foreach ($roles as $r) {
        if ((int)$r['id_rol'] === (int)$idRol) return htmlspecialchars($r['nombre']);
      }
      return '—';
    })(); ?></div>

    <div><strong>Filtro estado:</strong> <?= htmlspecialchars($estado) ?></div>
    <div><strong>Total:</strong> <?= count($usuarios) ?></div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:40px;">ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th style="width:90px;">Estado</th>
        <th style="width:120px;">Rol</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($usuarios)): ?>
        <tr><td colspan="5">No hay usuarios para esos filtros.</td></tr>
      <?php else: ?>
        <?php foreach ($usuarios as $u): ?>
          <tr>
            <td><?= (int)$u['id_usuario'] ?></td>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['correo']) ?></td>
            <td>
              <?php if (($u['estado'] ?? '') === 'activo'): ?>
                <span class="badge-ok">activo</span>
              <?php else: ?>
                <span class="badge-bad">inactivo</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($u['rol'] ?? '—') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
