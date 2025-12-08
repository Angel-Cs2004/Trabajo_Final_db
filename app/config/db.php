<?php
// app/config/db.php

$DB_HOST = 'localhost';       
$DB_USER = 'root';            
$DB_PASS = '';               
$DB_NAME = 'db_negocios_2025';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

if (!$conn->set_charset("utf8mb4")) {
    die('Error al configurar el charset: ' . $conn->error);
}
