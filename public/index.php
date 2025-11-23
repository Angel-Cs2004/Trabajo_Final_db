<?php
// public/index.php

require_once '../app/config/db.php';
require_once '../app/controllers/AuthController.php';

$controlador = new AuthController($conn);
$controlador->login();
