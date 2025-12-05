<?php

class Categoria
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obtenerTodas(): array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, estado, activo
                FROM categorias
                ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerTodasActivas(): array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, estado, activo
                FROM categorias
                WHERE activo = 1
                ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorId(int $id_categoria): ?array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, estado, activo
                FROM categorias
                WHERE id_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $categoria = $result->fetch_assoc();
        $stmt->close();

        return $categoria ?: null;
    }

    public function crearCategoria(string $nombre, ?string $descripcion = null, string $estado = 'activo', int $activo = 1): bool
    {
        $sql = "INSERT INTO categorias (nombre, descripcion, estado, activo)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("sssi", $nombre, $descripcion, $estado, $activo);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function editarCategoria(int $id_categoria, string $nombre, ?string $descripcion, string $estado, int $activo): bool
    {
        $sql = "UPDATE categorias
                SET nombre = ?, descripcion = ?, estado = ?, activo = ?
                WHERE id_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("sssii", $nombre, $descripcion, $estado, $activo, $id_categoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function desactivarCategoria(int $id_categoria): bool
    {
        $sql = "UPDATE categorias
                SET activo = 0, estado = 'inactivo'
                WHERE id_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_categoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function eliminarCategoria(int $id_categoria): bool
    {
        $sql = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_categoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

