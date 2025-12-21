<?php

class Roles
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // ========== BÁSICOS ==========

    public function obtenerTodos(): array
    {
        $sql = "SELECT id_rol, nombre, estado
                FROM roles
                ORDER BY nombre ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT id_rol, nombre, estado
                FROM roles
                WHERE id_rol = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $rol = $result->fetch_assoc();

        return $rol ?: null;
    }

    // ========== CREAR / ACTUALIZAR / ELIMINAR ==========

    // Ahora devuelve el ID del rol creado
    public function crear(string $nombre): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO roles (nombre, estado)
             VALUES (?, 'activo')"
        );
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        return $this->conn->insert_id;
    }

    public function actualizar(int $id, string $nombre): void
    {
        $stmt = $this->conn->prepare(
            "UPDATE roles
             SET nombre = ?
             WHERE id_rol = ?"
        );
        $stmt->bind_param("si", $nombre, $id);
        $stmt->execute();
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->conn->prepare("DELETE FROM roles WHERE id_rol=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // ========== PERMISOS ==========

    // Inserta en rol_tag_permiso según matriz [id_tag => [id_permiso1, id_permiso2, ...]]
    public function asignarPermisos(int $idRol, array $permisosPorTag): void
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO rol_tag_permiso (id_rol, id_tag, id_permiso)
             VALUES (?, ?, ?)"
        );

        foreach ($permisosPorTag as $idTag => $listaPermisos) {
            $idTag = (int)$idTag;
            if (!is_array($listaPermisos)) continue;

            foreach ($listaPermisos as $idPermiso) {
                $idPermiso = (int)$idPermiso;
                $stmt->bind_param("iii", $idRol, $idTag, $idPermiso);
                $stmt->execute();
            }
        }
    }

    // Borra permisos actuales y vuelve a insertar los nuevos
    public function reemplazarPermisos(int $idRol, array $permisosPorTag): void
    {
        $stmtDelete = $this->conn->prepare(
            "DELETE FROM rol_tag_permiso WHERE id_rol = ?"
        );
        $stmtDelete->bind_param("i", $idRol);
        $stmtDelete->execute();

        $this->asignarPermisos($idRol, $permisosPorTag);
    }

    // Para editar: obtener qué permisos tiene este rol
    public function obtenerPermisosPorRol(int $idRol): array
    {
        $sql = "SELECT id_tag, id_permiso
                FROM rol_tag_permiso
                WHERE id_rol = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idRol);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
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
