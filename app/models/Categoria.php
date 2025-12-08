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
        $sql = "SELECT id_categoria, nombre, descripcion, activo 
                FROM categorias
                ORDER BY nombre ASC";

        $result = $this->conn->query($sql);
        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerActivas(): array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, activo 
                FROM categorias
                WHERE activo = 1
                ORDER BY nombre ASC";

        $result = $this->conn->query($sql);
        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, activo
                FROM categorias
                WHERE id_categoria = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res ? $res->fetch_assoc() : null;

        $stmt->close();

        return $fila ?: null;
    }

    public function crear(string $nombre, ?string $descripcion, int $activo = 1): bool
    {
        $sql = "INSERT INTO categorias (nombre, descripcion, activo)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ssi", $nombre, $descripcion, $activo);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function actualizar(int $id, string $nombre, ?string $descripcion, int $activo): bool
    {
        $sql = "UPDATE categorias
                SET nombre = ?, descripcion = ?, activo = ?
                WHERE id_categoria = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ssii", $nombre, $descripcion, $activo, $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
