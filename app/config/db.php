<?php
// app/config/db.php

$DB_HOST = 'localhost';        // En Laragon normalmente es 'localhost'
$DB_USER = 'root';             // Usuario por defecto de MySQL en Laragon
$DB_PASS = '';                 // Contraseña (vacía si nunca la cambiaste)
$DB_NAME = 'db_negocios_2025'; // Nombre de tu base de datos

// Crear el objeto de conexión mysqli (orientado a objetos)
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Verificar si hubo error al conectar
if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Configurar el conjunto de caracteres (muy importante)
if (!$conn->set_charset("utf8mb4")) {
    die('Error al configurar el charset: ' . $conn->error);
}
