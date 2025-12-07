<?php

class Role
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

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

    public function crear(string $nombre)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO roles (nombre, estado)
             VALUES (?, 'activo')"
        );
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
    }

    public function actualizar(int $id, string $nombre)
    {
        $stmt = $this->conn->prepare(
            "UPDATE roles
             SET nombre = ?
             WHERE id_rol = ?"
        );
        $stmt->bind_param("si", $nombre, $id);
        $stmt->execute();
    }

    public function eliminar(int $id)
    {
        $stmt = $this->conn->prepare("DELETE FROM roles WHERE id_rol=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
