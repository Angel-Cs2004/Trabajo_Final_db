<?php

require_once __DIR__ . '/../models/negocio.php';
require_once __DIR__ . '/../models/usuarios.php';

class NegocioController
{
    private mysqli $conn;
    private Negocio $negocioModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->negocioModel = new Negocio($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void
    {
        $rol = $_SESSION['rol'] ?? null;
        $idUsuario = $_SESSION['id_usuario'] ?? null;

        if ($rol === 'admin' || $rol === 'super_admin') {
            $negocios = $this->negocioModel->obtenerTodos();
        } elseif ($rol === 'proveedor' && $idUsuario) {
            $negocios = $this->negocioModel->obtenerPorPropietario((int)$idUsuario);
        } else {
            $negocios = [];
        }

        require __DIR__ . '/../views/negocios/index.php';
    }

    public function listar(): void
    {
        // alias por si usas listar en el router
        $this->index();
    }

    public function crear(): void
    {
        $usuariosModel = new Usuarios($this->conn);
        $usuarios = $usuariosModel->obtenerTodos();

        require __DIR__ . '/../views/negocios/crear.php';
    }

    public function guardar(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $idPropietario = isset($_POST['id_propietario']) ? (int) $_POST['id_propietario'] : 0;

        // Checkbox "Activo" -> controlamos dos cosas:
        $activo = isset($_POST['estado_disponibilidad']) ? 1 : 0;
        $estadoDisponibilidad = $activo ? 'abierto' : 'cerrado';

        if ($nombre === '' || $idPropietario <= 0) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $this->negocioModel->crear(
            $nombre,
            $descripcion,
            $telefono,
            null, // imagen_logo por ahora
            $estadoDisponibilidad,
            $activo,
            $idPropietario
        );

        header('Location: index.php?c=negocio&a=listar');
        exit;
    }

    public function editar(): void
    {
        $idNegocio = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($idNegocio <= 0) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $negocio = $this->negocioModel->obtenerPorId($idNegocio);
        if (!$negocio) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $usuariosModel = new Usuarios($this->conn);
        $usuarios = $usuariosModel->obtenerTodos();

        require __DIR__ . '/../views/negocios/editar.php';
    }

    public function actualizar(): void
    {
        $idNegocio = isset($_POST['id_negocio']) ? (int) $_POST['id_negocio'] : 0;
        if ($idNegocio <= 0) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $idPropietario = !empty($_POST['id_propietario']) ? (int) $_POST['id_propietario'] : null;

        $activo = isset($_POST['estado_disponibilidad']) ? 1 : 0;
        $estadoDisponibilidad = $activo ? 'abierto' : 'cerrado';

        if ($nombre === '') {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $this->negocioModel->actualizar(
            $idNegocio,
            $nombre,
            $descripcion,
            $telefono,
            null,
            $estadoDisponibilidad,
            $activo,
            $idPropietario
        );

        header('Location: index.php?c=negocio&a=listar');
        exit;
    }

    public function perfil(): void
    {
        $idUsuario = $_SESSION['id_usuario'] ?? null;
        if (!$idUsuario) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $negocios = $this->negocioModel->obtenerPorPropietario((int)$idUsuario);
        require __DIR__ . '/../views/negocios/perfil.php';
    }
}
