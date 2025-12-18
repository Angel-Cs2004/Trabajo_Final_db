<?php

class ParametroImagen
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

    // Obtener todos los parámetros
    public function obtenerTodos(): array
    {
        $sql = "CALL sp_parametros_imagenes_listar()";
        $result = $this->conn->query($sql);

        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $this->limpiarResultadosCall();

        return $rows;
    }

    // Obtener un parámetro por ID
    public function obtenerPorId(int $id): ?array
    {
        $sql = "CALL sp_parametros_imagenes_obtener_por_id(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $result = $stmt->get_result();
        $dato = $result ? $result->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $dato ?: null;
    }

    // Crear un nuevo parámetro
    public function crear(
        ?string $nombre,
        string $etiqueta,
        int $ancho,
        int $alto,
        string $categoria,
        string $formatos
    ): bool {
        $sql = "CALL sp_parametros_imagenes_crear(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssiiss",
            $nombre,
            $etiqueta,
            $ancho,
            $alto,
            $categoria,
            $formatos
        );

        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();

        return $ok;
    }

    // Actualizar parámetro existente
    public function actualizar(
        int $id,
        ?string $nombre,
        string $etiqueta,
        int $ancho,
        int $alto,
        string $categoria,
        string $formatos
    ): bool {
        $sql = "CALL sp_parametros_imagenes_actualizar(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "issiiss",
            $id,
            $nombre,
            $etiqueta,
            $ancho,
            $alto,
            $categoria,
            $formatos
        );

        $ok = $stmt->execute();

        $stmt->close();
        $this->limpiarResultadosCall();

        return $ok;
    }
}