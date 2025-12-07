<?php

<<<<<<< HEAD
require_once __DIR__ . '/../models/negocio.php';
require_once __DIR__ . '/../models/usuarios.php';
=======
require_once __DIR__ . '/../models/Negocio.php';
>>>>>>> origin/security

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

<<<<<<< HEAD
        if ($rol === 'admin' || $rol === 'super_admin') {
            $negocios = $this->negocioModel->obtenerTodos();
        } elseif ($rol === 'proveedor' && $idUsuario) {
            $negocios = $this->negocioModel->obtenerPorPropietario((int)$idUsuario);
        } else {
            $negocios = [];
=======
    public function index()
    {
        $this->asegurarSesion();

        $modeloNegocio = new Negocio($this->conn);

        // super_admin y admin_negocio ven todos los negocios
        if ($_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'super_admin') {
            $negocios = $modeloNegocio->obtenerTodos();
        } else {
            // operador / invitado → solo los suyos (si aplica)
            $negocios = $modeloNegocio->obtenerPorPropietario((int)$_SESSION['id_usuario']);
>>>>>>> origin/security
        }

        require __DIR__ . '/../views/negocios/index.php';
    }

    public function listar(): void
    {
        // alias por si usas listar en el router
        $this->index();
    }

<<<<<<< HEAD
    public function crear(): void
    {
        $usuariosModel = new Usuarios($this->conn);
        $usuarios = $usuariosModel->obtenerTodos();
=======
        // Si es admin_negocio o super_admin → puede asignar propietario
        if ($_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'super_admin') {
            $modeloUsuario = new Usuarios($this->conn);
            $usuarios = $modeloUsuario->obtenerTodos();
        } else {
            // Si es propietario normal → solo él mismo
            $usuarios = [];
        }
>>>>>>> origin/security

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

<<<<<<< HEAD
        $this->negocioModel->crear(
            $nombre,
            $descripcion,
            $telefono,
            null, // imagen_logo por ahora
            $estadoDisponibilidad,
            $activo,
=======
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $estado       = $_POST['estado'] ?? 'activo';          // <select name="estado">
        $horaApertura = $_POST['hora_apertura'] ?? '';         // <input type="time" name="hora_apertura">
        $horaCierre   = $_POST['hora_cierre'] ?? '';           // <input type="time" name="hora_cierre">
        $imagen_logo  = null;                                  // luego metes upload si quieres

        // ADMIN_NEGOCIO / SUPER_ADMIN: puede asignar propietario
        if ($_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'super_admin') {
            $idPropietario = intval($_POST['id_propietario'] ?? 0);
        } else {
            $idPropietario = (int)$_SESSION['id_usuario'];
        }

        // Validación mínima
        if ($nombre === '' || $horaApertura === '' || $horaCierre === '' || $idPropietario <= 0) {
            header("Location: index.php?c=negocio&a=crear");
            exit;
        }

        $modeloNegocio = new Negocio($this->conn);

        $modeloNegocio->crear(
            $nombre,
            $descripcion,
            $imagen_logo,
            $estado,
            $horaApertura,
            $horaCierre,
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
        if ($_SESSION['rol'] !== 'admin_negocio' && $_SESSION['rol'] !== 'super_admin') {
            if ((int)$negocio['id_propietario'] !== (int)$_SESSION['id_usuario']) {
                header("Location: index.php?c=negocio&a=listar");
                exit;
            }
        }

        if ($_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'super_admin') {
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

        $id            = intval($_POST['id_negocio'] ?? 0);
        $nombre        = trim($_POST['nombre'] ?? '');
        $descripcion   = trim($_POST['descripcion'] ?? '');
        $estado        = $_POST['estado'] ?? 'activo';
        $horaApertura  = $_POST['hora_apertura'] ?? '';
        $horaCierre    = $_POST['hora_cierre'] ?? '';

        $modeloNegocio = new Negocio($this->conn);
        $negocioActual = $modeloNegocio->obtenerPorId($id);

        if (!$negocioActual) {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        // quién será el propietario luego de actualizar
        if ($_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'super_admin') {
            $idPropietario = intval($_POST['id_propietario'] ?? $negocioActual['id_propietario']);
        } else {
            $idPropietario = (int)$negocioActual['id_propietario'];
        }

        $imagen_logo = $negocioActual['imagen_logo']; // por ahora no cambiamos logo

        $modeloNegocio->actualizar(
            $id,
            $nombre,
            $descripcion,
            $imagen_logo,
            $estado,
            $horaApertura,
            $horaCierre,
>>>>>>> origin/security
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

    public function listar()
    {
        // Puedes simplemente reutilizar index()
        $this->index();
    }
}
