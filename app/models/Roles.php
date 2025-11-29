<?php

class Role
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Obtener todos los roles
    public function obtenerTodos(): array
    {
        $sql = "SELECT 
                    id_rol,
                    nombre,
                    descripcion,
                    es_superadmin
                FROM roles
                ORDER BY nombre ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener rol por ID
    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT 
                    id_rol,
                    nombre,
                    descripcion,
                    es_superadmin
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

    // Crear nuevo rol
    public function crear($nombre, $descripcion, $es_superadmin)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO roles (nombre, descripcion, es_superadmin)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param("ssi", $nombre, $descripcion, $es_superadmin);
        $stmt->execute();
    }

    // Actualizar rol existente
    public function actualizar($id, $nombre, $descripcion, $es_superadmin)
    {
        $stmt = $this->conn->prepare(
            "UPDATE roles
             SET nombre=?, descripcion=?, es_superadmin=?
             WHERE id_rol=?"
        );
        $stmt->bind_param("ssii", $nombre, $descripcion, $es_superadmin, $id);
        $stmt->execute();
    }

    // Eliminar rol
    public function eliminar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM roles WHERE id_rol=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
