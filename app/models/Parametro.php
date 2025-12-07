<?php

class ParametroImagen
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Obtener todos los parámetros
    public function obtenerTodos(): array
    {
        $sql = "SELECT *
                FROM parametros_imagenes
                ORDER BY nombre ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener un parámetro por ID
    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM parametros_imagenes
             WHERE id_parametro_imagen = ?
             LIMIT 1"
        );
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $dato = $result->fetch_assoc();
        $stmt->close();

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
        $stmt = $this->conn->prepare(
            "INSERT INTO parametros_imagenes 
            (nombre, etiqueta, ancho_px, alto_px, categoria, formatos_validos)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
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
        $stmt = $this->conn->prepare(
            "UPDATE parametros_imagenes
             SET nombre = ?, etiqueta = ?, ancho_px = ?, alto_px = ?, 
                 categoria = ?, formatos_validos = ?
             WHERE id_parametro_imagen = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssiissi",
            $nombre,
            $etiqueta,
            $ancho,
            $alto,
            $categoria,
            $formatos,
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
