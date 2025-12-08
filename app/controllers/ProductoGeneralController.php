<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/negocio.php';

class ProductoGeneralController
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
        $productos = $this->productoModel->obtenerTodosConDetalles();
        require __DIR__ . '/../views/producto/general/index.php';
    }

    public function crear(): void
    {
        $categoriaModel = new Categoria($this->conn);
        $negocioModel   = new Negocio($this->conn);

        $categorias = $categoriaModel->obtenerActivas();
        $negocios   = $negocioModel->obtenerTodos();

        require __DIR__ . '/../views/producto/general/crear.php';
    }

    public function guardar(): void
    {
        $nombre   = trim($_POST['nombre'] ?? '');
        $codigo   = trim($_POST['codigo'] ?? '');
        $precio   = (float) ($_POST['precio'] ?? 0);
        $url      = trim($_POST['url_imagen'] ?? '');
        $idCat    = isset($_POST['id_categoria']) ? (int) $_POST['id_categoria'] : 0;
        $idNeg    = isset($_POST['id_negocio']) ? (int) $_POST['id_negocio'] : 0;
        $activo   = isset($_POST['activo']) ? 1 : 0;

        if ($nombre === '' || $codigo === '' || $idCat <= 0 || $idNeg <= 0) {
            header('Location: index.php?c=productoGeneral&a=crear');
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

        header('Location: index.php?c=productoGeneral&a=listar');
        exit;
    }
}
