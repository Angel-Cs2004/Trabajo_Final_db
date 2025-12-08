<?php

class Categoria
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Listar todas las categorías
     */
    public function obtenerTodas(): array
    {
        $sql = "SELECT 
                    id_categoria,
                    nombre,
                    descripcion,
                    activo
                FROM categorias
                ORDER BY nombre ASC";
        
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Listar solo las activas (para selects de productos)
     */
    public function obtenerActivas(): array
    {
        $sql = "SELECT 
                    id_categoria,
                    nombre,
                    descripcion
                FROM categorias
                WHERE activo = 1
                ORDER BY nombre ASC";
        
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtener una categoría por ID
     */
    public function obtenerPorId(int $idCategoria): ?array
    {
        $sql = "SELECT 
                    id_categoria,
                    nombre,
                    descripcion,
                    activo
                FROM categorias
                WHERE id_categoria = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $dato = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $dato ?: null;
    }

    /**
     * Crear nueva categoría
     */
    public function crear(string $nombre, ?string $descripcion, int $activo): bool
    {
        $sql = "INSERT INTO categorias (nombre, descripcion, activo)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssi", $nombre, $descripcion, $activo);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    /**
     * Actualizar categoría
     */
    public function actualizar(
        int $idCategoria,
        string $nombre,
        ?string $descripcion,
        int $activo
    ): bool {
        $sql = "UPDATE categorias
                SET nombre = ?, descripcion = ?, activo = ?
                WHERE id_categoria = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssii", $nombre, $descripcion, $activo, $idCategoria);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    /**
     * Desactivar (baja lógica)
     */
    public function desactivar(int $idCategoria): bool
    {
        $sql = "UPDATE categorias SET activo = 0 WHERE id_categoria = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $idCategoria);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
