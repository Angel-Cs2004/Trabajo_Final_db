<?php

require_once __DIR__ . '/../models/ProductoNegocio.php';
require_once __DIR__ . '/../models/Negocio.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoNegocioController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    public function listar()
    {
        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        if ($idUsuario <= 0) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $productoNegocioModel = new ProductoNegocio($this->conn);

        // obtenerTodos ahora interpreta el parámetro como id_usuario propietario
        $productos = $productoNegocioModel->obtenerTodos($idUsuario);

        require __DIR__ . '/../views/productos/Negocio/index.php';
    }

    public function crear()
    {
        $categoriaModel = new Categoria($this->conn);
        $categorias = $categoriaModel->obtenerTodasActivas();

        require __DIR__ . '/../views/productos/Negocio/crear.php';
    }

    // Procesa el POST del formulario
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=productoNegocio&a=crear");
            exit;
        }

        $productoNegocioModel = new ProductoNegocio($this->conn);
        $negocioModel         = new Negocio($this->conn);

        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        if ($idUsuario <= 0) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        // obtenerPorPropietario ahora devuelve array de negocios
        $negocios = $negocioModel->obtenerPorPropietario($idUsuario);
        if (!$negocios || count($negocios) === 0) {
            // si no tiene negocios, lo mandamos a crear uno
            header("Location: index.php?c=negocio&a=crear");
            exit;
        }

        // Por ahora tomamos el primer negocio del propietario
        $negocio   = $negocios[0];
        $idNegocio = (int)$negocio['id_negocio'];

        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);

        // Validación mínima
        if ($nombre === '' || $precio <= 0 || $idCategoria <= 0) {
            header("Location: index.php?c=productoNegocio&a=crear");
            exit;
        }

        $productoNegocioModel->crearProducto(
            $idNegocio,
            $nombre,
            $precio,
            $urlImagen,
            $estado,
            $idCategoria
        );

        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    public function editar()
    {
        $id_producto = $_GET['id_producto'] ?? null;
        if (!$id_producto) {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        $productoNegocio = new ProductoNegocio($this->conn);
        $categoriaModel  = new Categoria($this->conn);

        $producto   = $productoNegocio->obtenerPorId((int)$id_producto);
        $categorias = $categoriaModel->obtenerTodasActivas();

        if (!$producto) {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        require __DIR__ . '/../views/productos/Negocio/editar.php';
    }

    // Procesa el POST del formulario de edición
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        $productoNegocio = new ProductoNegocio($this->conn);

        $id_producto = (int)($_POST['id_producto'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);

        if ($id_producto <= 0 || $nombre === '' || $precio <= 0 || $idCategoria <= 0) {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        $productoNegocio->editarProducto(
            $id_producto,
            $nombre,
            $precio,
            $urlImagen,
            $estado,
            $idCategoria
        );

        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    public function eliminar()
    {
        $id_producto = $_GET['id_producto'] ?? null;
        if ($id_producto) {
            $productoNegocio = new ProductoNegocio($this->conn);
            $productoNegocio->eliminarProducto((int)$id_producto);
        }

        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }
}
