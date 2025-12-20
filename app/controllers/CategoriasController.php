<?php

class CategoriasController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function listar()
    {
        $categoriaModel = new Categoria($this->conn);
        $categorias = $categoriaModel->obtenerTodas();

        require __DIR__ . '/../views/categorias/index.php';
    }

    public function crear()
    {
        require __DIR__ . '/../views/categorias/crear.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=categorias&a=crear");
            exit;
        }

        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        // por defecto las creamos 'activo'
        $estado      = $_POST['estado'] ?? 'inactivo';

        if (session_status() === PHP_SESSION_NONE) session_start();

        $categoriaModel = new Categoria($this->conn);
        $res = $categoriaModel->crearCategoria($nombre, $descripcion, $estado);

        $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
        $_SESSION['flash_msg']  = $res['msg'];

        header("Location: index.php?c=categorias&a=listar");
        exit;

    }

    public function editar()
    {
        $id_categoria = $_GET['id_categoria'] ?? null;
        if (!$id_categoria) {
            header("Location: index.php?c=categorias&a=listar");
            exit;
        }

        $categoriaModel = new Categoria($this->conn);
        $categoria = $categoriaModel->obtenerPorId((int)$id_categoria);

        if (!$categoria) {
            header("Location: index.php?c=categorias&a=listar");
            exit;
        }

        require __DIR__ . '/../views/categorias/editar.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=categorias&a=listar");
            exit;
        }

        $id_categoria = (int) ($_POST['id_categoria'] ?? 0);
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $estado       = $_POST['estado'] ?? 'inactivo';

       if (session_status() === PHP_SESSION_NONE) session_start();

        $categoriaModel = new Categoria($this->conn);
        $res = $categoriaModel->editarCategoria($id_categoria, $nombre, $descripcion, $estado);

        $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
        $_SESSION['flash_msg']  = $res['msg'];

        header("Location: index.php?c=categorias&a=listar");
        exit;

    }

    public function desactivar()
    {
        $id_categoria = $_GET['id_categoria'] ?? null;
        if ($id_categoria) {
            $categoriaModel = new Categoria($this->conn);
            $categoriaModel->desactivarCategoria((int)$id_categoria);
        }

        header("Location: index.php?c=categorias&a=listar");
        exit;
    }

public function eliminar()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id_categoria = $_GET['id_categoria'] ?? null;

    if ($id_categoria) {
        $categoriaModel = new Categoria($this->conn);
        $res = $categoriaModel->eliminarCategoria((int)$id_categoria);

        $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
        $_SESSION['flash_msg']  = $res['msg'];
    } else {
        $_SESSION['flash_tipo'] = 'error';
        $_SESSION['flash_msg']  = 'ID de categoría inválido.';
    }

    header("Location: index.php?c=categorias&a=listar");
    exit;
}

}
