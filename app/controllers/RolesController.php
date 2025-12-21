<?php

require_once __DIR__ . '/../models/Roles.php';     // Clase Role
require_once __DIR__ . '/../models/Tags.php';      // NUEVO
require_once __DIR__ . '/../models/Permisos.php';  // NUEVO

class RolesController
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

    private function asegurarSesionAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Debe estar logueado (ya no validas tipo de rol, sólo login)
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
    // ========== LISTAR ==========

    public function index()
    {
        $this->authorize('ROL', 'R');
        $this->asegurarSesionAdmin();

        $modelo = new Roles($this->conn);
        $roles = $modelo->obtenerTodos();

        

        // leer mensaje de error (si viene desde eliminar)
        $error = $_GET['error'] ?? null;

        require __DIR__ . '/../views/roles/index.php';
    }


    // ========== CREAR ==========

    public function crear()
    {
        $this->authorize('ROL', 'C');
        $this->asegurarSesionAdmin();

        $tagsModel = new Tags($this->conn);
        $permisosModel = new Permisos($this->conn);

        $tags = $tagsModel->obtenerTodos();
        $permisos = $permisosModel->obtenerTodos();

        require __DIR__ . '/../views/roles/crear.php';
    }

    public function guardar()
    {
        $this->authorize('ROL', 'U');
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $permisosPorTag = $_POST['permisos'] ?? []; // matriz [id_tag => [id_permiso...]]

        if ($nombre === '') {
            header("Location: index.php?c=roles&a=crear");
            exit;
        }

        $modelo = new Roles($this->conn);

        // Transacción para que rol + permisos se guarden juntos
        $this->conn->begin_transaction();

        try {
            $idRol = $modelo->crear($nombre);

            if (!empty($permisosPorTag)) {
                $modelo->asignarPermisos($idRol, $permisosPorTag);
            }

            $this->conn->commit();

            header("Location: index.php?c=roles&a=index");
            exit;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            // Podrías hacer log del error si quieres
            header("Location: index.php?c=roles&a=crear");
            exit;
        }
    }

    // ========== EDITAR / ACTUALIZAR ==========

    public function editar()
    {
        $this->asegurarSesionAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $modelo = new Roles($this->conn);
        $rol = $modelo->obtenerPorId($id);

        if (!$rol) {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        // Obtener tags, permisos y los permisos actuales de este rol
        $tagsModel = new Tags($this->conn);
        $permisosModel = new Permisos($this->conn);

        $tags = $tagsModel->obtenerTodos();
        $permisos = $permisosModel->obtenerTodos();
        $permisosRol = $modelo->obtenerPermisosPorRol($id);

        // Convertimos a estructura [id_tag][id_permiso] = true para marcar checkboxes
        $permisosMarcados = [];
        foreach ($permisosRol as $fila) {
            $idTag = $fila['id_tag'];
            $idPermiso = $fila['id_permiso'];
            $permisosMarcados[$idTag][$idPermiso] = true;
        }

        require __DIR__ . '/../views/roles/editar.php';
    }

    public function actualizar()
    {
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $id     = intval($_POST['id_rol'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $permisosPorTag = $_POST['permisos'] ?? [];

        if ($id <= 0 || $nombre === '') {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $modelo = new Roles($this->conn);

        $this->conn->begin_transaction();

        try {
            $modelo->actualizar($id, $nombre);
            $modelo->reemplazarPermisos($id, $permisosPorTag);

            $this->conn->commit();
            header("Location: index.php?c=roles&a=index");
            exit;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            header("Location: index.php?c=roles&a=editar&id=" . $id);
            exit;
        }
    }
    public function eliminar()
    {
        $this->asegurarSesionAdmin();

        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            header("Location: index.php?c=roles&a=index");
            exit;
        }

        $modelo = new Roles($this->conn);

        // 1) Verificar si hay usuarios asociados a este rol
        if ($modelo->tieneUsuariosAsociados($id)) {
            // No permitimos eliminar y mandamos un mensaje de error por GET
            header("Location: index.php?c=roles&a=index&error=rol_en_uso");
            exit;
        }

        // 2) Si no hay usuarios, procedemos a eliminar
        $this->conn->begin_transaction();

        try {
            // Primero borrar permisos del rol en rol_tag_permiso
            $stmtRolTagPerm = $this->conn->prepare(
                "DELETE FROM rol_tag_permiso WHERE id_rol = ?"
            );
            $stmtRolTagPerm->bind_param("i", $id);
            $stmtRolTagPerm->execute();

            // Luego borrar el rol
            $modelo->eliminar($id);

            $this->conn->commit();
        } catch (\Throwable $e) {
            $this->conn->rollback();
            // Podrías hacer log del error si quieres
        }

        header("Location: index.php?c=roles&a=index");
        exit;
    }

    public function tieneUsuariosAsociados(int $idRol): bool
    {
        $sql = "SELECT COUNT(*) AS total
                FROM usuario_rol
                WHERE id_rol = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idRol);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return ($row['total'] ?? 0) > 0;
    }


}
