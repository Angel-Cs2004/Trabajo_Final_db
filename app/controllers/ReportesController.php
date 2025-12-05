<?php

require_once __DIR__ . '/../models/Reportes.php';
require_once __DIR__ . '/../models/Negocio.php';

class ReportesController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    private function asegurarSesion()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    public function ReporteGeneral()
    {
        $this->asegurarSesion();

        $reportesModel = new Reportes($this->conn);

        // Filtros que te pueden llegar por GET o POST
        $idCategoria = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0;
        $precioMin   = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
        $precioMax   = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 0;
        $idNegocio   = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

        $porCategoria = [];
        $porRango     = [];

        if ($idCategoria > 0) {
            $porCategoria = $reportesModel->productosPorCategoria($idCategoria);
        }

        if ($precioMin >= 0 && $precioMax > 0 && $precioMax >= $precioMin) {
            $porRango = $reportesModel->productosRangoPrecio($precioMin, $precioMax, $idNegocio);
        }

        require __DIR__ . '/../views/Reportes/General/index.php';
    }


    public function ReporteNegocio()
    {
        $this->asegurarSesion();

        $reportesModel = new Reportes($this->conn);
        $negocioModel  = new Negocio($this->conn);

        $idUsuario = (int) $_SESSION['id_usuario'];

        if ($_SESSION['rol'] === 'super_admin' || $_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'admin') {
            $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;
        } else {
            $negociosPropio = $negocioModel->obtenerPorPropietario($idUsuario);
            $idNegocio = $negociosPropio ? (int)$negociosPropio[0]['id_negocio'] : 0;
        }

        $productos = [];
        $negociosDelPropietario = [];

        if ($idNegocio > 0) {
            $productos = $reportesModel->productosPorNegocio($idNegocio);
        }

        $negociosDelPropietario = $negocioModel->obtenerPorPropietario($idUsuario);

        require __DIR__ . '/../views/Reportes/Negocio/index.php';
    }
}
