<?php

require_once __DIR__ . '/../models/Roles.php';

class RolesController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Verifica sesión y privilegios de administrador
    private function asegurarSesionAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'super_admin') {
            header("Location: index.php?c=home&a=dashboardProveedor");
            exit;
        }
    }

    // Listar roles
    public function index()
    {
        $this->asegurarSesionAdmin();

        $modelo = new Role($this->conn);
        $roles = $modelo->obtenerTodos();

        require __DIR__ . '/../views/roles/index.php';
    }

    // Formulario de creación
    public function crear()
    {
        $this->asegurarSesionAdmin();
        require __DIR__ . '/../views/roles/crear.php';
    }

    // Guardar rol
    public function guardar()
    {
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $nombre         = trim($_POST['nombre'] ?? '');
        $descripcion    = trim($_POST['descripcion'] ?? '');

        if ($nombre === '') {
            header("Location: index.php?c=roles&a=crear");
            exit;
        }

        $modelo = new Role($this->conn);
        $modelo->crear($nombre, $descripcion);

        header("Location: index.php?c=roles&a=index");
        exit;
    }

    // Formulario de edición
    public function editar()
    {
        $this->asegurarSesionAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $modelo = new Role($this->conn);
        $rol = $modelo->obtenerPorId($id);

        require __DIR__ . '/../views/roles/editar.php';
    }

    // Actualizar rol
    public function actualizar()
    {
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $id            = intval($_POST['id_rol'] ?? 0);
        $nombre        = trim($_POST['nombre'] ?? '');
        $descripcion   = trim($_POST['descripcion'] ?? '');

        if ($id <= 0 || $nombre === '') {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $modelo = new Role($this->conn);
        $modelo->actualizar($id, $nombre, $descripcion);

        header("Location: index.php?c=roles&a=index");
        exit;
    }
}
