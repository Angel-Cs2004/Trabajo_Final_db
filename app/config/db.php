<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = 3306;

$DB_USER = 'negocios_app';
$DB_PASS = 'elyud el prosor';
$DB_NAME = 'db_negocios_2025';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
$conn->set_charset("utf8mb4");
