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
        require __DIR__ . '/../views/home/dashboard_admin.php';
    }

    public function dashboardProveedor()
    {
        require __DIR__ . '/../views/home/dashboard_admin.php';
    }
}
