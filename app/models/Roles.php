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
                    nombre
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
    public function crear($nombre, $descripcion)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO roles (nombre, descripcion)
             VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $nombre, $descripcion);
        $stmt->execute();
    }

    // Actualizar rol existente
    public function actualizar($id, $nombre, $descripcion)
    {
        $stmt = $this->conn->prepare(
            "UPDATE roles
             SET nombre=?, descripcion=?
             WHERE id_rol=?"
        );
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
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

