<?php
require_once __DIR__ . '/../app/config/db.php';

// 1. Obtener la lista de negocios para el <select>
$sqlNegocios = "SELECT id_negocio, nombre FROM negocios WHERE activo = 1";
$resultNegocios = $conn->query($sqlNegocios);

if ($resultNegocios === false) {
    die("Error al obtener negocios: " . $conn->error);
}

// 2. Leer el id_negocio enviado por GET (si existe)
$id_negocio = isset($_GET['id_negocio']) ? (int) $_GET['id_negocio'] : 0;

// 3. Si se eligió un negocio (>0), llamamos al SP
$resultReporte = null;

if ($id_negocio > 0) {
    // Usamos prepare para evitar problemas
    $stmt = $conn->prepare("CALL sp_reporte_productos_por_negocio(?)");
    if ($stmt === false) {
        die("Error al preparar el SP: " . $conn->error);
    }

    $stmt->bind_param("i", $id_negocio);

    if (!$stmt->execute()) {
        die("Error al ejecutar el SP: " . $stmt->error);
    }

    $resultReporte = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos por Negocio</title>
</head>
<body>
    <h2>Reporte de Productos por Negocio</h2>

    <!-- Formulario para elegir negocio -->
    <form method="GET" action="reporte_productos_negocio.php">
        <label for="id_negocio">Selecciona un negocio:</label>
        <select name="id_negocio" id="id_negocio">
            <option value="0">-- Elige un negocio --</option>
            <?php while ($neg = $resultNegocios->fetch_assoc()): ?>
                <option value="<?php echo $neg['id_negocio']; ?>"
                    <?php echo ($neg['id_negocio'] == $id_negocio) ? 'selected' : ''; ?>>
                    <?php echo $neg['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <hr>

    <?php if ($id_negocio > 0): ?>
        <h3>Resultados para el negocio ID <?php echo $id_negocio; ?></h3>

        <?php if ($resultReporte && $resultReporte->num_rows > 0): ?>
            <table border="1" cellpadding="6">
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                </tr>
                <?php while ($row = $resultReporte->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_producto']; ?></td>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo $row['codigo']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                        <td><?php echo $row['categoria']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay productos para este negocio.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Selecciona un negocio y haz clic en "Buscar" para ver sus productos.</p>
    <?php endif; ?>

</body>
</html>
<?php
// Liberar resultados
$resultNegocios->free();
if ($resultReporte !== null) {
    $resultReporte->free();
}
$conn->close();
?>
