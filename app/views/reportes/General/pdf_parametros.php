<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tablero de Parámetros</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    h1 { margin: 0 0 6px 0; font-size: 18px; }
    .meta { font-size: 11px; color: #555; margin-bottom: 14px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
    th { background: #f2f2f2; text-align: left; }
  </style>
</head>
<body>
  <h1>Tablero de Parámetros (Imágenes)</h1>
  <div class="meta">
    Total: <?= !empty($parametrosTablero) ? count($parametrosTablero) : 0 ?> |
    Generado: <?= date('d/m/Y H:i') ?>
  </div>

  <?php if (!empty($parametrosTablero)): ?>
    <table>
      <thead>
        <tr>
          <th style="width:60px;">ID</th>
          <th>Nombre</th>
          <th>Etiqueta</th>
          <th style="width:110px;">Categoría</th>
          <th style="width:70px;">Ancho</th>
          <th style="width:70px;">Alto</th>
          <th style="width:90px;">Formatos</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($parametrosTablero as $p): ?>
          <tr>
            <td><?= (int)($p['id_parametro_imagen'] ?? 0) ?></td>
            <td><?= htmlspecialchars($p['nombre'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['etiqueta'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
            <td><?= htmlspecialchars((string)($p['ancho_px'] ?? '-')) ?></td>
            <td><?= htmlspecialchars((string)($p['alto_px'] ?? '-')) ?></td>
            <td><?= htmlspecialchars($p['formatos_validos'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No hay parámetros registrados.</p>
  <?php endif; ?>
</body>
</html>
