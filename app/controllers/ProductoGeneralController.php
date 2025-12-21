<?php

require_once __DIR__ . '/../models/ProductoGeneral.php';
require_once __DIR__ . '/../models/Negocio.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoGeneralController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si quieres que SOLO usuarios logueados puedan ver esto:
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        // Si quisieras limitar a super_admin o admin_negocio:
        // if (($_SESSION['rol'] ?? '') !== 'super_admin') { ... }
    }

    private function authorize(string $mod, string $perm): void
    {
        if (!can($mod, $perm)) {
            http_response_code(403);
            exit('No autorizado');
        }
    }

    /**
     * Lista TODOS los productos de TODOS los negocios
     */
    public function listar()
    {
        $this->authorize('PRODUCTO_GEN', 'R');
        $modelo = new ProductoGeneral($this->conn);
        $productos = $modelo->obtenerTodos();

        require __DIR__ . '/../views/productos/General/index.php';
    }

    /**
     * Formulario para crear producto en cualquier negocio
     */
    public function crear()
    {
        $this->authorize('PRODUCTO_GEN', 'C');
        $categoriaModel = new Categoria($this->conn);
        $negocioModel   = new Negocio($this->conn);

        $categorias = $categoriaModel->obtenerTodasActivas(); // asumiendo que ya existe
        $negocios   = $negocioModel->obtenerTodos();          // todos los negocios activos/inactivos

        require __DIR__ . '/../views/productos/General/crear.php';
    }

    /**
     * Procesa el POST del formulario general
     */
    public function guardar()
    {
        $this->authorize('PRODUCTO_GEN', 'U');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=productoGeneral&a=listar");
            exit;
        }

        $modeloProducto = new ProductoGeneral($this->conn);

        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        $idNegocio   = (int)($_POST['id_negocio'] ?? 0);

        if (
            $nombre === '' ||
            $precio <= 0 ||
            $idCategoria <= 0 ||
            $idNegocio <= 0
        ) {
            // podrías mandar un mensaje de error en sesión/flash
            header("Location: index.php?c=productoGeneral&a=crear");
            exit;
        }

        $res = $modeloProducto->crearProducto(
            $idNegocio,
            $nombre,
            $precio,
            $urlImagen,
            $estado,
            $idCategoria
        );

        $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
        $_SESSION['flash_msg']  = $res['msg'];

        header("Location: index.php?c=productoGeneral&a=listar");
        exit;

    }

    /**
     * Formulario de edición global
     */
    public function editar()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=productoGeneral&a=listar");
            exit;
        }

        $modeloProducto = new ProductoGeneral($this->conn);
        $categoriaModel = new Categoria($this->conn);
        $negocioModel   = new Negocio($this->conn);

        $producto   = $modeloProducto->obtenerPorId($id);
        $categorias = $categoriaModel->obtenerTodasActivas();
        $negocios   = $negocioModel->obtenerTodos();

        if (!$producto) {
            header("Location: index.php?c=productoGeneral&a=listar");
            exit;
        }

        require __DIR__ . '/../views/productos/General/editar.php';
    }

    /**
     * Procesa el POST de edición global
     */
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=productoGeneral&a=listar");
            exit;
        }

        $modeloProducto = new ProductoGeneral($this->conn);

        $idProducto  = (int)($_POST['id_producto'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        $idNegocio   = (int)($_POST['id_negocio'] ?? 0);

        if (
            $idProducto <= 0 ||
            $nombre === '' ||
            $precio <= 0 ||
            $idCategoria <= 0 ||
            $idNegocio <= 0
        ) {
            header("Location: index.php?c=productoGeneral&a=listar");
            exit;
        }
        $res = $modeloProducto->editarProducto(
            $idProducto,
            $idNegocio,
            $nombre,
            $precio,
            $urlImagen,
            $estado,
            $idCategoria
        );

        $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
        $_SESSION['flash_msg']  = $res['msg'];

        header("Location: index.php?c=productoGeneral&a=listar");
        exit;

    }

    /**
     * Eliminar producto (modo general)
     */
    public function eliminar()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $modeloProducto = new ProductoGeneral($this->conn);
            $modeloProducto->eliminar($id);
        }

        header("Location: index.php?c=productoGeneral&a=listar");
        exit;
    }
}
