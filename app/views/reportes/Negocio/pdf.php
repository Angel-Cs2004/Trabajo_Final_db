<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Productos por Negocio</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .sub {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .categoria {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>
<body>

<h1>Mis Productos por Negocio</h1>
<div class="sub">
    Reporte generado el <?= date('d/m/Y H:i') ?>
</div>

<?php if (!empty($productos)): ?>

    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['producto'] ?? '-') ?></td>
                    <td>S/ <?= number_format((float)($p['precio'] ?? 0), 2) ?></td>
                    <td><?= htmlspecialchars($p['estado'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No hay productos para mostrar.</p>

<?php endif; ?>

<div class="footer">
    Sistema de Reportes – Vista Propietario
</div>

</body>
</html>
