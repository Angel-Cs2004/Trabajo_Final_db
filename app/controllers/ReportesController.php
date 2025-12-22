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

    // =========================
    // BLOQUE 1: REPORTE GENERAL (PRODUCTOS)
    // =========================
    $runGeneral  = isset($_GET['run_general']) ? (int)$_GET['run_general'] : 0;

    $idCategoria = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0;
    $precioMin   = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
    $precioMax   = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 0;
    $idNegocio   = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    $categorias = $m->listarCategorias();
    $negocios   = $m->listarNegocios();

    // ✅ AL INICIO NO CARGA NADA
    $porCategoria = [];
    $porRango     = [];

    if ($runGeneral === 1) {
        $porCategoria = $m->productosPorCategoria($idCategoria);
        $porRango     = $m->productosRangoPrecio($precioMin, $precioMax, $idNegocio);
    }

    // =========================
    // BLOQUE 2: REPORTE USUARIOS
    // =========================
    $runUsuarios = isset($_GET['run_usuarios']) ? (int)$_GET['run_usuarios'] : 0;

    $idRolU   = isset($_GET['id_rol_u']) ? (int)$_GET['id_rol_u'] : 0;
    $estadoU  = $_GET['estado_u'] ?? 'todos';

    $roles    = $m->listarRoles();

    // ✅ AL INICIO NO CARGA NADA
    $usuarios = [];

    if ($runUsuarios === 1) {
        $usuarios = $m->usuariosConRol($idRolU, $estadoU);
    }
    // =========================
// BLOQUE 3: DETALLE TIENDA (para usarlo dentro de ReporteGeneral view)
// =========================
$runDetalle = isset($_GET['run_detalle']) ? (int)$_GET['run_detalle'] : 0;

$idPropietario = isset($_GET['id_propietario']) ? (int)$_GET['id_propietario'] : 0;
$idNegocioDet  = isset($_GET['id_negocio_det']) ? (int)$_GET['id_negocio_det'] : 0;

// SIEMPRE cargar propietarios (para que el combo NO salga vacío)
$propietarios = $m->listarPropietariosConNegocio();

// negocios del propietario seleccionado
$negociosDet = [];
if ($idPropietario > 0) {
    $negociosDet = $m->negociosPorPropietario($idPropietario);
}

// Resultados detalle
$negocioInfo = null;
$productosPorCategoria = [];
$resumen = null;
$disponibilidad = null;

if ($runDetalle === 1 && $idNegocioDet > 0) {
    $negocioInfo = $m->detalleNegocio($idNegocioDet);

    if ($negocioInfo) {
        $disponibilidad = $m->calcularDisponibilidad(
            $negocioInfo['estado'] ?? 'inactivo',
            $negocioInfo['hora_apertura'] ?? null,
            $negocioInfo['hora_cierre'] ?? null
        );

        $productos = $m->productosPorNegocio($idNegocioDet);

        foreach ($productos as $p) {
            $cat = $p['categoria'] ?? 'Sin categoría';
            $productosPorCategoria[$cat][] = $p;
        }

        $resumen = $m->resumenPreciosProductos($productos);
    }
} 
    // =========================
    // BLOQUE 4 y 5: TABLEROS (categorías y parámetros)
    // =========================
    $categoriasTablero = $m->categoriasTablero();
    $parametrosTablero = $m->parametrosTablero();

    // OJO: tu carpeta real es /reportes/General/
    require __DIR__ . '/../views/reportes/General/index.php';
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
    

public function DetalleTienda()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    $negocioInfo = null;
    $productosPorCategoria = [];
    $resumen = null;
    $disponibilidad = null;

    if ($idNegocio > 0) {
        $negocioInfo = $m->detalleNegocio($idNegocio);

        if ($negocioInfo) {
            $disponibilidad = $m->calcularDisponibilidad(
                $negocioInfo['estado'] ?? 'inactivo',
                $negocioInfo['hora_apertura'] ?? null,
                $negocioInfo['hora_cierre'] ?? null
            );

            $productos = $m->productosPorNegocio($idNegocio);

            foreach ($productos as $p) {
                $cat = $p['categoria'] ?? 'Sin categoría';
                $productosPorCategoria[$cat][] = $p;
            }

            $resumen = $m->resumenPreciosProductos($productos);
        }
    }

    require __DIR__ . '/../views/reportes/MiTienda/index.php';
}


public function PdfDetalleTienda()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idPropietario = isset($_GET['id_propietario']) ? (int)$_GET['id_propietario'] : 0;
    $idNegocio     = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    if ($idNegocio <= 0) {
        $_SESSION['flash_msg']  = 'Selecciona un negocio para generar el PDF.';
        $_SESSION['flash_tipo'] = 'error';
        header('Location: index.php?c=reporte&a=detalleTienda&id_propietario=' . $idPropietario);
        exit;
    }

    $negocioInfo = $m->detalleNegocio($idNegocio);
    if (!$negocioInfo) {
        $_SESSION['flash_msg']  = 'No se encontró el negocio.';
        $_SESSION['flash_tipo'] = 'error';
        header('Location: index.php?c=reporte&a=detalleTienda&id_propietario=' . $idPropietario);
        exit;
    }

    $disponibilidad = $m->calcularDisponibilidad(
        $negocioInfo['estado'] ?? 'inactivo',
        $negocioInfo['hora_apertura'] ?? null,
        $negocioInfo['hora_cierre'] ?? null
    );

    $productos = $m->productosPorNegocio($idNegocio);

    $productosPorCategoria = [];
    foreach ($productos as $p) {
        $cat = $p['categoria'] ?? 'Sin categoría';
        if (!isset($productosPorCategoria[$cat])) $productosPorCategoria[$cat] = [];
        $productosPorCategoria[$cat][] = $p;
    }

    $resumen = $m->resumenPreciosProductos($productos);

    ob_start();
    require __DIR__ . '/../views/reportes/DetalleTienda/pdf.php';
    $html = ob_get_clean();

    $this->generarPDF($html, 'detalle_tienda_' . $idNegocio . '.pdf');
}



public function listarMisNegocios(int $idUsuario): array
{
    $sql = "
        SELECT n.id_negocio, n.nombre
        FROM negocios n
        WHERE n.id_propietario = ?
        ORDER BY n.nombre ASC
    ";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) return [];

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();

    return $data;
}

public function productosPorNegocioMio(int $idUsuario, int $idNegocio): array
{
    $sql = "
        SELECT
            c.nombre AS categoria,
            p.nombre AS producto,
            p.precio,
            p.estado
        FROM productos p
        INNER JOIN categorias c ON c.id_categoria = p.id_categoria
        INNER JOIN negocios n   ON n.id_negocio   = p.id_negocio
        WHERE p.id_negocio = ?
          AND n.id_propietario = ?
        ORDER BY c.nombre ASC, p.nombre ASC
    ";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) return [];

    $stmt->bind_param("ii", $idNegocio, $idUsuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();

    return $data;
}
public function ReporteNegocioMio()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    // ✅ SOLO MIS NEGOCIOS
    $negocios = $m->listarMisNegocios($idUsuario);

    // ✅ SOLO PRODUCTOS DE MI NEGOCIO (y que sea mío)
    $productos = [];
    if ($idNegocio > 0) {
        $productos = $m->productosPorNegocioMio($idUsuario, $idNegocio);
    }

    require __DIR__ . '/../views/reportes/Negocio/index.php';
}
// =====================================================
// (X) REPORTES "MIOS" (solo propietario)
// =====================================================























// (1) Mis Negocios
public function MisNegocios()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);

    $runMisNegocios = isset($_GET['run_mis_negocios']) ? (int)$_GET['run_mis_negocios'] : 0;
    $estado = $_GET['estado'] ?? 'todos';     // 'activo','inactivo','todos'
    $busqueda = trim($_GET['busqueda'] ?? ''); // nombre

    $misNegocios = [];
    if ($runMisNegocios === 1) {
        $misNegocios = $m->misNegocios($idUsuario, $estado, $busqueda);
    }

require __DIR__ . '/../views/reportes/MisNegocios/index.php';

}

public function PdfMisNegocios()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);

    $estado   = $_GET['estado'] ?? 'todos';
    $busqueda = trim($_GET['busqueda'] ?? '');

    $misNegocios = $m->misNegocios($idUsuario, $estado, $busqueda);

    $html = $this->htmlDesdeVista(
        __DIR__ . '/../views/reportes/MisNegocios/pdf.php',
        compact('estado', 'busqueda', 'misNegocios')
    );

    $this->generarPDF($html, 'mis_negocios.pdf');
}


// (2) Mis Productos (de todas mis tiendas; filtro opcional por tienda)
public function MisProductos()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);

    $runMisProductos = isset($_GET['run_mis_productos']) ? (int)$_GET['run_mis_productos'] : 0;
    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0; // 0 = todas

    // combo: SOLO MIS NEGOCIOS
    $negocios = $m->listarMisNegocios($idUsuario);

    $productos = [];
    if ($runMisProductos === 1) {
        $productos = $m->misProductos($idUsuario, $idNegocio);
    }

    require __DIR__ . '/../views/reportes/MisProductos/index.php';
}

public function PdfMisProductos()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    $negocios = $m->listarMisNegocios($idUsuario);
    $productos = $m->misProductos($idUsuario, $idNegocio);

    $html = $this->htmlDesdeVista(
        __DIR__ . '/../views/reportes/MisProductos/pdf.php',
        compact('idNegocio', 'negocios', 'productos')
    );

    $this->generarPDF($html, 'mis_productos.pdf');
}

// (3) Mi Tienda (ficha completa de 1 negocio mío)

    public function PdfReporteNegocioMio()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    if ($idNegocio <= 0) {
        $_SESSION['flash_msg']  = 'Selecciona un negocio para generar el PDF.';
        $_SESSION['flash_tipo'] = 'error';
        header('Location: index.php?c=reporte&a=reporteNegocioMio');
        exit;
    }

    // combo: SOLO MIS NEGOCIOS
    $negocios = $m->listarMisNegocios($idUsuario);

    // SOLO PRODUCTOS DE MI NEGOCIO (y que sea mío)
    $productos = $m->productosPorNegocioMio($idUsuario, $idNegocio);

    // Render del PDF (tu archivo YA EXISTE y está bien)
    $html = $this->htmlDesdeVista(
        __DIR__ . '/../views/reportes/Negocio/pdf.php',
        compact('idNegocio', 'negocios', 'productos')
    );

    $this->generarPDF($html, 'mis_productos_negocio_' . $idNegocio . '.pdf');
}
public function MiTienda()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    // Combo: SOLO mis negocios
    $negocios = $m->listarMisNegocios($idUsuario);

    $negocioInfo = null;
    $productosPorCategoria = [];
    $disponibilidad = null;
    $resumen = null;

    if ($idNegocio > 0) {

        // Seguridad: que el negocio sea mío
        $negocioInfo = $m->detalleNegocioMio($idUsuario, $idNegocio);

        if ($negocioInfo) {

            $disponibilidad = $m->calcularDisponibilidad(
                $negocioInfo['estado'],
                $negocioInfo['hora_apertura'],
                $negocioInfo['hora_cierre']
            );

            $productos = $m->productosPorNegocioMio($idUsuario, $idNegocio);

            foreach ($productos as $p) {
                $cat = $p['categoria'] ?? 'Sin categoría';
                $productosPorCategoria[$cat][] = $p;
            }

            $resumen = $m->resumenPreciosProductos($productos);
        }
    }

    require __DIR__ . '/../views/reportes/MiTienda/index.php';
}
public function PdfMiTienda()
{
    $this->asegurarSesion();
    $m = new Reportes($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    $idNegocio = isset($_GET['id_negocio']) ? (int)$_GET['id_negocio'] : 0;

    if ($idNegocio <= 0) {
        $_SESSION['flash_msg']  = 'Selecciona una tienda para generar el PDF.';
        $_SESSION['flash_tipo'] = 'error';
        header('Location: index.php?c=reporte&a=miTienda');
        exit;
    }

    $negocioInfo = $m->detalleNegocioMio($idUsuario, $idNegocio);
    if (!$negocioInfo) {
        $_SESSION['flash_msg']  = 'La tienda no existe o no te pertenece.';
        $_SESSION['flash_tipo'] = 'error';
        header('Location: index.php?c=reporte&a=miTienda');
        exit;
    }

    $disponibilidad = $m->calcularDisponibilidad(
        $negocioInfo['estado'],
        $negocioInfo['hora_apertura'],
        $negocioInfo['hora_cierre']
    );

    $productos = $m->productosPorNegocioMio($idUsuario, $idNegocio);

    $productosPorCategoria = [];
    foreach ($productos as $p) {
        $cat = $p['categoria'] ?? 'Sin categoría';
        $productosPorCategoria[$cat][] = $p;
    }

    $resumen = $m->resumenPreciosProductos($productos);

    $html = $this->htmlDesdeVista(
        __DIR__ . '/../views/reportes/MiTienda/pdf.php',
        compact('negocioInfo', 'disponibilidad', 'productosPorCategoria', 'resumen')
    );

    $this->generarPDF($html, 'mi_tienda_' . $idNegocio . '.pdf');
}



}
