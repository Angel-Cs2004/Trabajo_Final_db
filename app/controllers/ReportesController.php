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

        // Filtros que llegan por GET (podrías cambiar a POST si quieres)
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
        $rol       = $_SESSION['rol'] ?? '';

        // super_admin y admin_negocio pueden elegir negocio por GET
        if ($rol === 'super_admin' || $rol === 'admin_negocio') {
            $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;
        } else {
            // operador_negocio / invitado_reportes → primer negocio del propietario (si tiene)
            $negociosPropio = $negocioModel->obtenerPorPropietario($idUsuario);
            $idNegocio = $negociosPropio ? (int)$negociosPropio[0]['id_negocio'] : 0;
        }

        $productos              = [];
        $negociosDelPropietario = [];

        if ($idNegocio > 0) {
            $productos = $reportesModel->productosPorNegocio($idNegocio);
        }

        // Lista de negocios del usuario logueado (para combos en la vista)
        $negociosDelPropietario = $negocioModel->obtenerPorPropietario($idUsuario);

        require __DIR__ . '/../views/Reportes/Negocio/index.php';
    }
}
<?php
// app/controllers/ReportesController.php

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

    /**
     * Reporte general de productos por rango de precios (usa SP sp_reporte_productos_rango_precio)
     */
    public function ReporteGeneral(): void
    {
        $negocioModel = new Negocio($this->conn);
        $negocios     = $negocioModel->obtenerTodos();

        $resultados = [];

        $filtros = [
            'precio_min' => '',
            'precio_max' => '',
            'id_negocio' => 0,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $precioMin = isset($_POST['precio_min']) ? (float)$_POST['precio_min'] : 0;
            $precioMax = isset($_POST['precio_max']) ? (float)$_POST['precio_max'] : 0;
            $idNegocio = isset($_POST['id_negocio']) ? (int)$_POST['id_negocio'] : 0;

            $filtros['precio_min'] = $precioMin;
            $filtros['precio_max'] = $precioMax;
            $filtros['id_negocio'] = $idNegocio;

            if ($precioMin >= 0 && $precioMax > 0 && $precioMax >= $precioMin) {
                $stmt = $this->conn->prepare("CALL sp_reporte_productos_rango_precio(?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param('ddi', $precioMin, $precioMax, $idNegocio);
                    $stmt->execute();

                    $res = $stmt->get_result();
                    if ($res) {
                        $resultados = $res->fetch_all(MYSQLI_ASSOC);
                    }

                    $stmt->close();

                    // limpieza de resultados múltiples por usar SP
                    while ($this->conn->more_results() && $this->conn->next_result()) {;}
                }
            }
        }

        require __DIR__ . '/../views/reportes/general/index.php';
    }

    /**
     * Reporte de productos por negocio (usa SP sp_reporte_productos_por_negocio)
     */
    public function ReporteNegocio(): void
    {
        $negocioModel = new Negocio($this->conn);

        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        $rol       = $_SESSION['rol'] ?? '';

        // Si quisieras filtrar por rol, aquí lo puedes ajustar.
        // Por ahora dejamos sencillo: admin ve todos, los demás igual.
        $negocios = $negocioModel->obtenerTodos();
        // Si quieres que sólo el propietario vea sus negocios:
        // $negocios = $negocioModel->obtenerPorPropietario($idUsuario);

        $idNegocioSeleccionado = 0;
        $productos             = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idNegocioSeleccionado = isset($_POST['id_negocio']) ? (int)$_POST['id_negocio'] : 0;

            if ($idNegocioSeleccionado > 0) {
                $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_negocio(?)");
                if ($stmt) {
                    $stmt->bind_param('i', $idNegocioSeleccionado);
                    $stmt->execute();

                    $res = $stmt->get_result();
                    if ($res) {
                        $productos = $res->fetch_all(MYSQLI_ASSOC);
                    }

                    $stmt->close();

                    // limpieza de resultados múltiples por usar SP
                    while ($this->conn->more_results() && $this->conn->next_result()) {;}
                }
            }
        }

        require __DIR__ . '/../views/reportes/negocio/index.php';
    }
}
