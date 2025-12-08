<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/negocio.php';

class ProductoNegocioController
{
    private mysqli $conn;
    private Producto $productoModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->productoModel = new Producto($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function listar(): void
    {
        $idUsuario = $_SESSION['id_usuario'] ?? null;
        if (!$idUsuario) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $productos = $this->productoModel->obtenerPorPropietario((int)$idUsuario);
        require __DIR__ . '/../views/producto/negocio/index.php';
    }

    public function crear(): void
    {
        $idUsuario = $_SESSION['id_usuario'] ?? null;
        if (!$idUsuario) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $categoriaModel = new Categoria($this->conn);
        $negocioModel   = new Negocio($this->conn);

        $categorias = $categoriaModel->obtenerActivas();
        $negocios   = $negocioModel->obtenerPorPropietario((int)$idUsuario);

        require __DIR__ . '/../views/producto/negocio/crear.php';
    }

    public function guardar(): void
    {
        $idUsuario = $_SESSION['id_usuario'] ?? null;
        if (!$idUsuario) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $codigo   = trim($_POST['codigo'] ?? '');
        $precio   = (float) ($_POST['precio'] ?? 0);
        $url      = trim($_POST['url_imagen'] ?? '');
        $idCat    = isset($_POST['id_categoria']) ? (int) $_POST['id_categoria'] : 0;
        $idNeg    = isset($_POST['id_negocio']) ? (int) $_POST['id_negocio'] : 0;
        $activo   = isset($_POST['activo']) ? 1 : 0;

        if ($nombre === '' || $codigo === '' || $idCat <= 0 || $idNeg <= 0) {
            header('Location: index.php?c=productoNegocio&a=crear');
            exit;
        }

        $this->productoModel->crear(
            $nombre,
            $codigo,
            $precio,
            $url !== '' ? $url : null,
            $idCat,
            $idNeg,
            $activo
        );

        header('Location: index.php?c=productoNegocio&a=listar');
        exit;
    }
}
