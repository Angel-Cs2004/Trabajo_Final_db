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
    <h1>Reporte de Usuarios</h1>
    <div class="meta">Generado: <?= date('Y-m-d H:i') ?> | Usuario: <?= htmlspecialchars($_SESSION['nombre'] ?? '-') ?></div>
  </div>

  <div class="meta">Filtros: id_rol=<?= (int)$idRol ?> | estado=<?= htmlspecialchars($estado) ?></div>

  <?php if (!empty($usuarios)): ?>
    <table>
      <thead><tr><th>Nombre</th><th>Correo</th><th>Identificación</th><th>Teléfono</th><th>Rol</th><th>Estado</th></tr></thead>
      <tbody>
        <?php foreach($usuarios as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['correo']) ?></td>
            <td><?= htmlspecialchars($u['identificacion'] ?? '-') ?></td>
            <td><?= htmlspecialchars($u['telefono'] ?? '-') ?></td>
            <td><?= htmlspecialchars($u['rol'] ?? '-') ?></td>
            <td><?= htmlspecialchars($u['estado'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="meta">Sin resultados.</div>
  <?php endif; ?>
</body>
</html>
