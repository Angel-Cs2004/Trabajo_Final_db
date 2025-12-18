<?php

class Negocio
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * IMPORTANTE: cuando haces CALL con mysqli, a veces queda un result-set extra.
     * Esto evita "Commands out of sync" en la siguiente consulta.
     */
    private function limpiarResultadosCall(): void
    {
        while ($this->conn->more_results() && $this->conn->next_result()) {
            $extra = $this->conn->use_result();
            if ($extra instanceof mysqli_result) {
                $extra->free();
            }
        }
    }

    /**
     * Antes: SELECT + foreach en PHP.
     * Ahora: SP con filtros y disponibilidad calculada en BD.
     */
    public function obtenerTodos(): array
    {
        // Defaults: sin filtros, orden DESC como tú lo tenías
        return $this->obtenerConFiltros(0, 'todos', 'todos', '', 'DESC');
    }

    /**
     * Método extra útil para reportes/filtros (lo puedes usar en controladores).
     * No rompe tu obtenerTodos() original.
     */
    public function obtenerConFiltros(
        int $idPropietario = 0,
        string $estado = 'todos',           // 'activo','inactivo','todos'
        string $disponibilidad = 'todos',   // 'abierto','cerrado','todos'
        string $busqueda = '',              // '' sin filtro
        string $orden = 'DESC'              // 'ASC' o 'DESC'
    ): array {
        $sql = "CALL sp_negocios_listar(?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('issss', $idPropietario, $estado, $disponibilidad, $busqueda, $orden);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return [];
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        $this->limpiarResultadosCall();

        return $rows;
    }

    /**
     * Antes: SELECT WHERE propietario = ?
     * Ahora: mismo SP pero con p_id_propietario
     */
    public function obtenerPorPropietario(int $idPropietario): array
    {
        return $this->obtenerConFiltros($idPropietario, 'todos', 'todos', '', 'DESC');
    }

    /**
     * Antes: SELECT WHERE id = ?
     * Ahora: CALL sp_negocios_obtener_por_id
     */
    public function obtenerPorId(int $idNegocio): ?array
    {
        $sql = "CALL sp_negocios_obtener_por_id(?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $idNegocio);

        if (!$stmt->execute()) {
            $stmt->close();
            $this->limpiarResultadosCall();
            return null;
        }

        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;

        $stmt->close();
        $this->limpiarResultadosCall();

        return $row ?: null;
    }

    /**
     * Antes: INSERT directo
     * Ahora: CALL sp_negocios_crear
     */
    public function crear(
        string $nombre,
        string $descripcion,
        ?string $imagen_logo,
        string $estado,
        string $hora_apertura,
        string $hora_cierre,
        int $idPropietario
    ): bool {
        $sql = "CALL sp_negocios_crear(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        // tipos: s s s s s s i
        $stmt->bind_param(
            'ssssssi',
            $nombre,
            $descripcion,
            $estado,
            $imagen_logo,
            $hora_apertura,
            $hora_cierre,
            $idPropietario
        );

        $ok = $stmt->execute();
        $stmt->close();
        $this->limpiarResultadosCall();

        return $ok;
    }

    /**
     * Antes: UPDATE con 2 ramas
     * Ahora: 1 CALL.
     * Regla: si $idPropietario es null => mandamos 0 (no cambia propietario)
     */
    public function actualizar(
        int $idNegocio,
        string $nombre,
        string $descripcion,
        ?string $imagen_logo,
        string $estado,
        string $hora_apertura,
        string $hora_cierre,
        ?int $idPropietario = null
    ): bool {
        $sql = "CALL sp_negocios_actualizar(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $prop = ($idPropietario === null) ? 0 : $idPropietario;

        // i s s s s s s i
        $stmt->bind_param(
            'issssssi',
            $idNegocio,
            $nombre,
            $descripcion,
            $estado,
            $imagen_logo,
            $hora_apertura,
            $hora_cierre,
            $prop
        );

        $ok = $stmt->execute();
        $stmt->close();
        $this->limpiarResultadosCall();

        return $ok;
    }
}
