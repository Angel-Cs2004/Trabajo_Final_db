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
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $dato = $result->fetch_assoc();

        return $dato ?: null;
    }

    // Crear un nuevo parámetro
    public function crear($etiqueta, $tipo, $ancho, $alto, $tamano, $categoria, $formatos, $activo)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO parametros_imagenes 
            (etiqueta, tipo, ancho_px, alto_px, tamano_kb, categoria_admin, formatos_validos, activo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssiiissi",
            $etiqueta, $tipo, $ancho, $alto, $tamano, $categoria, $formatos, $activo
        );

        $stmt->execute();
    }

    // Actualizar parámetro existente
    public function actualizar($id, $etiqueta, $tipo, $ancho, $alto, $tamano, $categoria, $formatos, $activo)
    {
        $stmt = $this->conn->prepare(
            "UPDATE parametros_imagenes
             SET etiqueta=?, tipo=?, ancho_px=?, alto_px=?, tamano_kb=?, 
                 categoria_admin=?, formatos_validos=?, activo=?
             WHERE id_parametro_imagen=?"
        );

        $stmt->bind_param(
            "ssiiissii",
            $etiqueta, $tipo, $ancho, $alto, $tamano,
            $categoria, $formatos, $activo, $id
        );

        $stmt->execute();
    }
}
