<?php

require_once __DIR__ . '/../models/Reportes.php';

// Dompdf (composer)
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportesController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    private function asegurarSesion(): void
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    private function generarPDF(string $html, string $filename): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    private function htmlDesdeVista(string $vista, array $vars = []): string
    {
        extract($vars);
        ob_start();
        require $vista;
        return ob_get_clean();
    }

    // =========================
    // (1) REPORTE GENERAL (ya existía) + PDF
    // =========================
    public function ReporteGeneral()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idCategoria = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0;
        $precioMin   = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
        $precioMax   = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 0;
        $idNegocio   = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

        $categorias = $m->listarCategorias();
        $negocios   = $m->listarNegocios();

        $porCategoria = [];
        $porRango     = [];

        if ($idCategoria > 0) $porCategoria = $m->productosPorCategoria($idCategoria);
        if ($precioMax > 0 && $precioMax >= $precioMin) $porRango = $m->productosRangoPrecio($precioMin, $precioMax, $idNegocio);

        require __DIR__ . '/../views/Reportes/General/index.php';
    }

    public function PdfReporteGeneral()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idCategoria = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0;
        $precioMin   = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
        $precioMax   = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 0;
        $idNegocio   = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

        $categorias = $m->listarCategorias();
        $negocios   = $m->listarNegocios();

        $porCategoria = ($idCategoria > 0) ? $m->productosPorCategoria($idCategoria) : [];
        $porRango     = ($precioMax > 0 && $precioMax >= $precioMin) ? $m->productosRangoPrecio($precioMin, $precioMax, $idNegocio) : [];

        $html = $this->htmlDesdeVista(__DIR__ . '/../views/Reportes/General/pdf.php', compact(
            'idCategoria','precioMin','precioMax','idNegocio','categorias','negocios','porCategoria','porRango'
        ));

        $this->generarPDF($html, 'reporte_general.pdf');
    }

    // =========================
    // (2) REPORTE NEGOCIO (ya existía) + PDF
    // =========================
    public function ReporteNegocio()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;
        $negocios  = $m->listarNegocios();

        $productos = ($idNegocio > 0) ? $m->productosPorNegocio($idNegocio) : [];

        require __DIR__ . '/../views/Reportes/Negocio/index.php';
    }

    public function PdfReporteNegocio()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;
        $negocios  = $m->listarNegocios();
        $productos = ($idNegocio > 0) ? $m->productosPorNegocio($idNegocio) : [];

        $html = $this->htmlDesdeVista(__DIR__ . '/../views/Reportes/Negocio/pdf.php', compact(
            'idNegocio','negocios','productos'
        ));

        $this->generarPDF($html, 'reporte_negocio.pdf');
    }

    // =========================
    // (3) NUEVO: RESUMEN DE TIENDAS + PDF
    // =========================
    public function ResumenTiendas()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $resumen = $m->resumenTiendas();

        require __DIR__ . '/../views/Reportes/Tiendas/index.php';
    }

    public function PdfResumenTiendas()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $resumen = $m->resumenTiendas();

        $html = $this->htmlDesdeVista(__DIR__ . '/../views/Reportes/Tiendas/pdf.php', compact('resumen'));
        $this->generarPDF($html, 'reporte_resumen_tiendas.pdf');
    }

    // =========================
    // (4) NUEVO: USUARIOS + PDF
    // =========================
    public function Usuarios()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idRol  = isset($_GET['id_rol']) ? (int)$_GET['id_rol'] : 0;
        $estado = $_GET['estado'] ?? 'todos';

        $roles   = $m->listarRoles();
        $usuarios = $m->usuariosConRol($idRol, $estado);

        require __DIR__ . '/../views/Reportes/Usuarios/index.php';
    }

    public function PdfUsuarios()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idRol  = isset($_GET['id_rol']) ? (int)$_GET['id_rol'] : 0;
        $estado = $_GET['estado'] ?? 'todos';

        $roles   = $m->listarRoles();
        $usuarios = $m->usuariosConRol($idRol, $estado);

        $html = $this->htmlDesdeVista(__DIR__ . '/../views/Reportes/Usuarios/pdf.php', compact('idRol','estado','roles','usuarios'));
        $this->generarPDF($html, 'reporte_usuarios.pdf');
    }

    // =========================
    // (5) NUEVO: ROLES Y PERMISOS + PDF
    // =========================
    public function RolesPermisos()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idRol = isset($_GET['id_rol']) ? (int)$_GET['id_rol'] : 0;
        $tag   = $_GET['tag'] ?? 'todos';

        $roles = $m->listarRoles();
        $tags  = $m->listarTags();
        $data  = $m->rolesPermisos($idRol, $tag);

        require __DIR__ . '/../views/Reportes/RolesPermisos/index.php';
    }

    public function PdfRolesPermisos()
    {
        $this->asegurarSesion();
        $m = new Reportes($this->conn);

        $idRol = isset($_GET['id_rol']) ? (int)$_GET['id_rol'] : 0;
        $tag   = $_GET['tag'] ?? 'todos';

        $roles = $m->listarRoles();
        $tags  = $m->listarTags();
        $data  = $m->rolesPermisos($idRol, $tag);

        $html = $this->htmlDesdeVista(__DIR__ . '/../views/Reportes/RolesPermisos/pdf.php', compact('idRol','tag','roles','tags','data'));
        $this->generarPDF($html, 'reporte_roles_permisos.pdf');
    }
}
