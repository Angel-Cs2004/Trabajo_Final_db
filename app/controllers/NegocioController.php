<?php

require_once __DIR__ . '/../models/Negocio.php';
require_once __DIR__ . '/../models/Usuarios.php';

class NegocioController
{
    private mysqli $conn;
    private Negocio $negocioModel;
    private Usuarios $usuariosModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->negocioModel = new Negocio($conn);
        $this->usuariosModel = new Usuarios($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    /**
     * Listado:
     * - admin / super_admin -> todos los negocios
     * - proveedor          -> sólo sus negocios
     */
    public function index(): void
    {
        $rol = $_SESSION['rol'] ?? '';
        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);

        if ($rol === 'proveedor') {
            $negocios = $this->negocioModel->obtenerPorPropietario($idUsuario);
        } else {
            $negocios = $this->negocioModel->obtenerTodos();
        }

        require __DIR__ . '/../views/negocios/index.php';
    }

    /**
     * Vista para "Mi negocio" / "Mis negocios"
     */
    public function perfil(): void
    {
        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        $negocios = $this->negocioModel->obtenerPorPropietario($idUsuario);

        require __DIR__ . '/../views/negocios/perfil.php';
    }

    public function crear(): void
    {
        // Usamos el modelo Usuarios que ya lista usuarios con rol
        $usuarios = $this->usuariosModel->obtenerTodos();
        require __DIR__ . '/../views/negocios/crear.php';
    }

    public function guardar(): void
    {
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $telefono     = trim($_POST['telefono'] ?? '');
        $idPropietario = (int)($_POST['id_propietario'] ?? 0);

        // Checkbox "Activo" (estado_disponibilidad + activo)
        $checked = isset($_POST['estado_disponibilidad']);
        $estadoDisponibilidad = $checked ? 'abierto' : 'cerrado';
        $activo = $checked ? 1 : 0;

        if ($nombre === '' || $idPropietario <= 0) {
            header('Location: index.php?c=negocio&a=crear');
            exit;
        }

        $ok = $this->negocioModel->crear(
            $nombre,
            $descripcion ?: null,
            $telefono ?: null,
            null,                   // imagen_logo (por ahora null)
            $estadoDisponibilidad,
            $idPropietario,
            $activo
        );

        header('Location: index.php?c=negocio&a=listar');
        exit;
    }

    public function editar(): void
    {
        $idNegocio = (int)($_GET['id'] ?? 0);
        if ($idNegocio <= 0) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $negocio = $this->negocioModel->obtenerPorId($idNegocio);
        if (!$negocio) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $usuarios = $this->usuariosModel->obtenerTodos();
        require __DIR__ . '/../views/negocios/editar.php';
    }

    public function actualizar(): void
    {
        $idNegocio    = (int)($_POST['id_negocio'] ?? 0);
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $telefono     = trim($_POST['telefono'] ?? '');
        $idPropietario = (int)($_POST['id_propietario'] ?? 0);

        $checked = isset($_POST['estado_disponibilidad']);
        $estadoDisponibilidad = $checked ? 'abierto' : 'cerrado';
        $activo = $checked ? 1 : 0;

        if ($idNegocio <= 0 || $nombre === '' || $idPropietario <= 0) {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }

        $this->negocioModel->actualizar(
            $idNegocio,
            $nombre,
            $descripcion ?: null,
            $telefono ?: null,
            null,                 // imagen_logo (por ahora)
            $estadoDisponibilidad,
            $activo,
            $idPropietario
        );

        header('Location: index.php?c=negocio&a=listar');
        exit;
    }
}
