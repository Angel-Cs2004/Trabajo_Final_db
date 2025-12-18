<?php

class Categoria
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    private function limpiarResultadosCall(): void
    {
        while ($this->conn->more_results() && $this->conn->next_result()) {
            $extra = $this->conn->use_result();
            if ($extra instanceof mysqli_result) {
                $extra->free();
            }
        }
    }

    public function obtenerTodas(): array
    {
        $sql = "CALL sp_categorias_listar()";
        $result = $this->conn->query($sql);
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $this->limpiarResultadosCall();
        return $rows;
    }

    public function obtenerTodasActivas(): array
    {
        $sql = "CALL sp_categorias_listar_activas()";
        $result = $this->conn->query($sql);
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $this->limpiarResultadosCall();
        return $rows;
    }

    public function obtenerPorId(int $id_categoria): ?array
    {
        $sql = "CALL sp_categorias_obtener_por_id(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id_categoria);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $result = $stmt->get_result();
        $categoria = $result ? $result->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $categoria ?: null;
    }

    public function crearCategoria(
        string $nombre,
        ?string $descripcion = null,
        string $estado = 'activo'
    ): bool {
        $sql = "CALL sp_categorias_crear(?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("sss", $nombre, $descripcion, $estado);
        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();
        return $ok;
    }

    public function editarCategoria(
        int $id_categoria,
        string $nombre,
        ?string $descripcion,
        string $estado
    ): bool {
        $sql = "CALL sp_categorias_editar(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("isss", $id_categoria, $nombre, $descripcion, $estado);
        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();
        return $ok;
    }

    public function desactivarCategoria(int $id_categoria): bool
    {
        $sql = "CALL sp_categorias_desactivar(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_categoria);
        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();
        return $ok;
    }

    public function eliminarCategoria(int $id_categoria): bool
    {
        $sql = "CALL sp_categorias_eliminar(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_categoria);
        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();
        return $ok;
    }

    public function buscarPorNombre(string $nombre): ?array
    {
        $sql = "CALL sp_categorias_buscar_por_nombre(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('s', $nombre);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $res = $stmt->get_result();
        $dato = $res ? $res->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $dato ?: null;
    }

    public function crearRapida(string $nombre): ?int
    {
        $sql = "CALL sp_categorias_crear_rapida(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('s', $nombre);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $row && isset($row['id_categoria']) ? (int)$row['id_categoria'] : null;
    }
}
