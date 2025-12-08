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
        $sql = "SELECT id_categoria, nombre, descripcion, estado
                FROM categorias
                ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerTodasActivas(): array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, estado
                FROM categorias
                WHERE estado = 'activo'
                ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorId(int $id_categoria): ?array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, estado
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

    public function crearCategoria(
        string $nombre,
        ?string $descripcion = null,
        string $estado = 'activo'
    ): bool {
        $sql = "INSERT INTO categorias (nombre, descripcion, estado)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("sss", $nombre, $descripcion, $estado);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function editarCategoria(
        int $id_categoria,
        string $nombre,
        ?string $descripcion,
        string $estado
    ): bool {
        $sql = "UPDATE categorias
                SET nombre = ?, descripcion = ?, estado = ?
                WHERE id_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("sssi", $nombre, $descripcion, $estado, $id_categoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function desactivarCategoria(int $id_categoria): bool
    {
        $sql = "UPDATE categorias
                SET estado = 'inactivo'
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
    public function buscarPorNombre(string $nombre): ?array
    {
        $sql = "SELECT * FROM categorias WHERE nombre = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $res  = $stmt->get_result();
        $dato = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return $dato ?: null;
    }

    /**
     * Crea una categoría básica en estado 'activo'
     */
    public function crearRapida(string $nombre): ?int
    {
        $sql = "INSERT INTO categorias (nombre, estado) VALUES (?, 'activo')";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('s', $nombre);
        $ok = $stmt->execute();

        if (!$ok) {
            $stmt->close();
            return null;
        }

        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

}
