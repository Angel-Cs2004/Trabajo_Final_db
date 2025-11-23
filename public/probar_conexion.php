<?php
// public/probar_conexion.php

// Incluir el archivo de conexión
require_once __DIR__ . '/../app/config/db.php';

// Si llegó hasta aquí, la conexión se creó bien
echo "<h2>Conexión a la base de datos exitosa ✅</h2>";

// Consulta simple
$sql = "SELECT NOW() AS fecha_hora_actual";
$result = $conn->query($sql);

if ($result === false) {
    die("Error al ejecutar la consulta de prueba: " . $conn->error);
}

$row = $result->fetch_assoc();

echo "<p>La fecha y hora en MySQL es: <strong>" . $row['fecha_hora_actual'] . "</strong></p>";

// Cerrar el resultado 
$result->free();

