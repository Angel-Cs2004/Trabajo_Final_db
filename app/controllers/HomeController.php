<?php

class HomeController
{
    private $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        session_start();

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    public function dashboardAdmin()
    {
        if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'super_admin') {
            header("Location: index.php?c=home&a=dashboardProveedor");
            exit;
        }

        require __DIR__ . '/../views/home/dashboard_admin.php';
    }

    public function dashboardProveedor()
    {
        if ($_SESSION['rol'] !== 'proveedor') {
            header("Location: index.php?c=home&a=dashboardAdmin");
            exit;
        }

        require __DIR__ . '/../views/home/dashboard_prove.php';
    }
}
