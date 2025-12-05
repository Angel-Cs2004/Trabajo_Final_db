<?php

require_once __DIR__ . '/../models/ProductoNegocio.php';
require_once __DIR__ . '/../models/Negocio.php';
require_once __DIR__ . '/../models/Categoria.php';
class ProductoNegocioController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function listar()
    {
        //vamos a crear un objeto de la clase ProductoNegocio
        //y llamar al metodo obtenerTodos   
        $productoNegocioModel = new ProductoNegocio($this->conn);
        $productos = $productoNegocioModel->obtenerTodos($_SESSION['id_usuario']);

        
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

        $idUsuario = $_SESSION['id_usuario'] ?? null;
        if (!$idUsuario) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $negocio = $negocioModel->obtenerPorPropietario($idUsuario); // SELECT * FROM negocios WHERE id_propietario = ? LIMIT 1
        if (!$negocio) {
            header("Location: index.php?c=negocio&a=crear");
            exit;
        }

        $idNegocio   = (int) $negocio['id_negocio'];
        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float) ($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int) ($_POST['id_categoria'] ?? 0);

        // (podrías validar aquí nombre/precio/categoría)

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

        $id_producto = (int) $_POST['id_producto'];
        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float) ($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int) ($_POST['id_categoria'] ?? 0);

        $productoNegocio->editarProducto(
            $id_producto,
            $nombre,
            $precio,
            $urlImagen,
            $estado,
            $idCategoria   // si decides permitir cambiar categoría también
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
