<?php

require_once __DIR__ . '/../models/Negocio.php';
require_once __DIR__ . '/../models/Usuarios.php';

class NegocioController
{
    private $conn; 

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

    private function asegurarSesion()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    public function index()
    {
        $this->asegurarSesion();

        $modeloNegocio = new Negocio($this->conn);

        if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'super_admin') {
            $negocios = $modeloNegocio->obtenerTodos();
        } else {
            $negocios = $modeloNegocio->obtenerPorPropietario($_SESSION['id_usuario']);
        }

        require __DIR__ . '/../views/negocios/index.php';
    }

    public function crear()
    {
        $this->asegurarSesion();

        // Si es admin → puede asignar propietario
        if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'super_admin') {
            $modeloUsuario = new Usuarios($this->conn);
            $usuarios = $modeloUsuario->obtenerTodos();
        } else {
            // Si es propietario → solo él mismo
            $usuarios = [];
        }

        require __DIR__ . '/../views/negocios/crear.php';
    }

    public function guardar()
    {
        $this->asegurarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        $nombre       = trim($_POST['nombre']);
        $descripcion  = trim($_POST['descripcion']);
        $telefono     = trim($_POST['telefono']);
        $estado       = $_POST['estado_disponibilidad'] ?? 'cerrado';
        $imagen_logo  = null;

        // ADMIN: puede asignar propietario
        if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'super_admin') {
            $idPropietario = intval($_POST['id_propietario'] ?? 0);
        } else {
            // PROPIETARIO: se auto-asigna
            $idPropietario = $_SESSION['id_usuario'];
        }

        if ($nombre === '' || $telefono === '' || $idPropietario <= 0) {
            header("Location: index.php?c=negocio&a=crear");
            exit;
        }

        $modeloNegocio = new Negocio($this->conn);

        $modeloNegocio->crear(
            $nombre,
            $descripcion,
            $telefono,
            $imagen_logo,
            $estado,
            1,
            $idPropietario
        );

        header("Location: index.php?c=negocio&a=listar");
        exit;
    }

    public function editar()
    {
        $this->asegurarSesion();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        $modeloNegocio = new Negocio($this->conn);
        $negocio = $modeloNegocio->obtenerPorId($id);

        if (!$negocio) {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        // Propietario solo puede editar lo suyo
        if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'super_admin') {
            if ($negocio['id_propietario'] != $_SESSION['id_usuario']) {
                header("Location: index.php?c=negocio&a=listar");
                exit;
            }
        }

        if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'super_admin') {
            $modeloUsuario = new Usuarios($this->conn);
            $usuarios = $modeloUsuario->obtenerTodos();
        } else {
            $usuarios = [];
        }

        require __DIR__ . '/../views/negocios/editar.php';
    }

    public function actualizar()
    {
        $this->asegurarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        $id            = intval($_POST['id_negocio']);
        $nombre        = trim($_POST['nombre']);
        $descripcion   = trim($_POST['descripcion']);
        $telefono      = trim($_POST['telefono']);
        $estado        = $_POST['estado_disponibilidad'] ?? 'cerrado';
        $activo        = isset($_POST['activo']) ? intval($_POST['activo']) : 1;

        $modeloNegocio = new Negocio($this->conn);
        $negocioActual = $modeloNegocio->obtenerPorId($id);

        if (!$negocioActual) {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'super_admin') {
            $idPropietario = intval($_POST['id_propietario']);
        } else {
            $idPropietario = $negocioActual['id_propietario'];
        }

        $imagen_logo = $negocioActual['imagen_logo'];

        $modeloNegocio->actualizar(
            $id,
            $nombre,
            $descripcion,
            $telefono,
            $imagen_logo,
            $estado,
            $activo,
            $idPropietario
        );

        header("Location: index.php?c=negocio&a=listar");
        exit;
    }

    public function perfil()
    {
        $this->asegurarSesion();

        $modeloNegocio = new Negocio($this->conn);

        require __DIR__ . '/../views/negocios/perfil.php';
    }
}
