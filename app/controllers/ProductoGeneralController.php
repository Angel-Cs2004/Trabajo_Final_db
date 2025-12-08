<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Negocio.php';

class ProductoGeneralController
{
    private mysqli $conn;
    private Producto $productoModel;
    private Categoria $categoriaModel;
    private Negocio $negocioModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->productoModel  = new Producto($conn);
        $this->categoriaModel = new Categoria($conn);
        $this->negocioModel   = new Negocio($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    /**
     * Listar todos los productos (vista admin)
     */
    public function listar(): void
    {
        $productos = $this->productoModel->obtenerTodos();
        require __DIR__ . '/../views/producto/general/index.php';
    }

    public function crear(): void
    {
        $categorias = $this->categoriaModel->obtenerActivas();
        $negocios   = $this->negocioModel->obtenerTodos();
        require __DIR__ . '/../views/producto/general/crear.php';
    }

    public function guardar(): void
    {
        $nombre     = trim($_POST['nombre'] ?? '');
        $codigo     = trim($_POST['codigo'] ?? '');
        $precio     = (float)($_POST['precio'] ?? 0);
        $urlImagen  = trim($_POST['url_imagen'] ?? '');
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        $idNegocio   = (int)($_POST['id_negocio'] ?? 0);

        if ($nombre === '' || $codigo === '' || $precio <= 0 || $idCategoria <= 0 || $idNegocio <= 0) {
            header('Location: index.php?c=productoGeneral&a=crear');
            exit;
        }

        $this->productoModel->crear(
            $nombre,
            $codigo,
            $precio,
            $urlImagen ?: null,
            $idCategoria,
            $idNegocio
        );

        header('Location: index.php?c=productoGeneral&a=listar');
        exit;
    }
}
