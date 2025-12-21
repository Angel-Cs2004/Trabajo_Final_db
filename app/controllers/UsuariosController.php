<?php

require_once __DIR__ . '/../models/Usuarios.php';
require_once __DIR__ . '/../helpers/PasswordHelper.php';

class UsuariosController
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

    // Verifica sesión y permisos de administrador
    private function asegurarSesionAdmin()
    {
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

    // Listar usuarios
    public function index()
    {
        $this->authorize('USUARIO', 'R');
        $this->asegurarSesionAdmin();

        $modelo = new Usuarios($this->conn);
        $usuarios = $modelo->obtenerTodos();

        require __DIR__ . '/../views/usuarios/index.php';
    }

    // Formulario de creación
    public function crear()
    {
        $this->authorize('USUARIO', 'C');
        $this->asegurarSesionAdmin();

        $modelo = new Usuarios($this->conn);
        $rolesUsuarios = $modelo->obtenerRoles();

        require __DIR__ . '/../views/usuarios/crear.php';
    }

    // Guardar nuevo usuario
    public function guardar()
    {
        $this->authorize('USUARIO', 'U');
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=usuarios&a=index");
            exit;
        }

        $nombre         = trim($_POST['nombre'] ?? '');
        $correo         = trim($_POST['correo'] ?? '');
        $identificacion = trim($_POST['identificacion'] ?? '');
        $telefono       = trim($_POST['telefono'] ?? '');
        $password       = trim($_POST['clave'] ?? '');
        $rolNombre      = trim($_POST['rol'] ?? '');      // ← nombre del rol
        $estado         = ($_POST['activo'] ?? '0') == '1' ? 'activo' : 'inactivo';

        if ($nombre === '' || $correo === '' || $password === '' || $rolNombre === '') {
            header("Location: index.php?c=usuarios&a=crear");
            exit;
        }

        $modelo = new Usuarios($this->conn);

        // Convertir nombre → id_rol
        $idRol = $modelo->obtenerIdRolPorNombre($rolNombre);

        // HASHEAR la contraseña para nuevos usuarios
        $passwordHash = PasswordHelper::hash($password);

        // Guardar usuario con id_rol (int)
        $modelo->crear($nombre, $correo, $identificacion, $telefono, $passwordHash, $estado, $idRol);

        header("Location: index.php?c=usuarios&a=index");
        exit;
    }

    // Formulario de edición
    public function editar()
    {
        $this->asegurarSesionAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=usuarios&a=index");
            exit;
        }

        $modelo = new Usuarios($this->conn);

        $error = $_GET['error'] ?? null;
        $usuario       = $modelo->obtenerPorId($id);
        $rolesUsuarios = $modelo->obtenerRoles();

        require __DIR__ . '/../views/usuarios/editar.php';
    }

    // Actualizar usuario
    public function actualizar()
    {
        
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=usuarios&a=index");
            exit;
        }

        $id             = intval($_POST['id_usuario'] ?? 0);
        $nombre         = trim($_POST['nombre'] ?? '');
        $correo         = trim($_POST['correo'] ?? '');
        $identificacion = trim($_POST['identificacion'] ?? '');
        $telefono       = trim($_POST['telefono'] ?? '');
        $estado = ($_POST['estado'] ?? 'inactivo') === 'activo' ? 'activo' : 'inactivo';
        $rolNombre      = trim($_POST['rol'] ?? '');      // ← nombre del rol
        $password       = trim($_POST['clave'] ?? '');
        $passwordConfirm = trim($_POST['clave_confirm'] ?? '');

        $modelo = new Usuarios($this->conn);

        // Convertir nombre → id_rol ANTES de actualizar
        $idRol = $modelo->obtenerIdRolPorNombre($rolNombre);

        if ($password === '') {
            // Actualizar sin cambiar la contraseña
            $modelo->actualizarSinClave($id, $nombre, $correo, $identificacion, $telefono, $estado, $idRol);
        } else {
            // HASHEAR la nueva contraseña
            if ($password !== $passwordConfirm) {
                header("Location: index.php?c=usuarios&a=editar&id=$id&error=no_coinciden");
                exit;
            }
            $passwordHash = PasswordHelper::hash($password);
            // Actualizar incluyendo nueva clave hasheada
            $modelo->actualizar($id, $nombre, $correo, $identificacion, $telefono, $passwordHash, $estado, $idRol);
        }

        header("Location: index.php?c=usuarios&a=index");
        exit;
    }
}
