<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Negocio.php';

class ProductoNegocioController
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
     * Mis productos:
     * - proveedor: productos de SUS negocios
     * - admin/super_admin: igual que arriba (pueden ver todo pero en este módulo
     *   lo tratamos como "mis productos" por propietario de sesión).
     */
    public function listar(): void
    {
        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        $productos = $this->productoModel->obtenerPorPropietario($idUsuario);

        require __DIR__ . '/../views/producto/negocio/index.php';
    }

    public function crear(): void
    {
        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        $rol = $_SESSION['rol'] ?? '';

        // Si es proveedor, solo sus negocios. Si es admin, todos.
        if ($rol === 'proveedor') {
            $negocios = $this->negocioModel->obtenerPorPropietario($idUsuario);
        } else {
            $negocios = $this->negocioModel->obtenerTodos();
        }

        $categorias = $this->categoriaModel->obtenerActivas();

        require __DIR__ . '/../views/producto/negocio/crear.php';
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
            header('Location: index.php?c=productoNegocio&a=crear');
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

        header('Location: index.php?c=productoNegocio&a=listar');
        exit;
    }
}
