<?php

class CategoriasController
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

    private function authorize(string $mod, string $perm): void
    {
        if (!can($mod, $perm)) {
            http_response_code(403);
            exit('No autorizado');
        }
    }
    
    public function listar()
    {
        $this->authorize('CATEGORIA', 'R');
        $categoriaModel = new Categoria($this->conn);
        $categorias = $categoriaModel->obtenerTodas();

        require __DIR__ . '/../views/categorias/index.php';
    }

    public function crear()
    {
        $this->authorize('CATEGORIA', 'C');
        require __DIR__ . '/../views/categorias/crear.php';
    }

    public function guardar()
    {
        $this->authorize('CATEGORIA', 'U');
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
