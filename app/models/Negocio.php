<?php

class Negocio
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Listar todos los negocios activos
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT 
                    n.*,
                    u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                WHERE n.activo = 1
                ORDER BY n.fecha_creacion DESC, n.id_negocio DESC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Negocios por propietario
     */
    public function obtenerPorPropietario(int $idPropietario): array
    {
        $sql = "SELECT 
                    n.*,
                    u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                WHERE n.id_propietario = ?
                  AND n.activo = 1
                ORDER BY n.fecha_creacion DESC, n.id_negocio DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $idPropietario);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $datos;
    }

    /**
     * Negocio por ID
     */
    public function obtenerPorId(int $idNegocio): ?array
    {
        $sql = "SELECT 
                    n.*,
                    u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                WHERE n.id_negocio = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $idNegocio);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $row ?: null;
    }

    /**
     * Crear negocio usando SP sp_insertar_negocio
     * - La SP NO recibe teléfono ni activo, se completan luego por UPDATE
     */
    public function crear(
        string $nombre,
        ?string $descripcion,
        ?string $telefono,
        ?string $imagen_logo,
        string $estadoDisponibilidad,
        int $idPropietario,
        int $activo = 1
    ): bool {

        // 1) Llamada al SP
        $sql = "CALL sp_insertar_negocio(?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssssi",
            $nombre,
            $descripcion,
            $imagen_logo,
            $estadoDisponibilidad,
            $idPropietario
        );

        $ok = $stmt->execute();
        $stmt->close();

        // Limpieza por SP
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        if (!$ok) {
            return false;
        }

        // 2) Obtener último id_negocio insertado
        $idNegocio = $this->conn->insert_id;
        if ($idNegocio <= 0) {
            return true; // al menos se insertó
        }

        // 3) Completar teléfono y activo
        $sqlUpdate = "UPDATE negocios
                      SET telefono = ?, activo = ?
                      WHERE id_negocio = ?";

        $stmt2 = $this->conn->prepare($sqlUpdate);
        if (!$stmt2) {
            return false;
        }

        $stmt2->bind_param("sii", $telefono, $activo, $idNegocio);
        $ok2 = $stmt2->execute();
        $stmt2->close();

        return $ok2;
    }

    /**
     * Actualizar negocio (sin SP, con UPDATE normal)
     */
    public function actualizar(
        int $idNegocio,
        string $nombre,
        ?string $descripcion,
        ?string $telefono,
        ?string $imagen_logo,
        string $estadoDisponibilidad,
        int $activo,
        ?int $idPropietario = null
    ): bool {

        if ($idPropietario !== null) {
            $sql = "UPDATE negocios
                    SET nombre = ?,
                        descripcion = ?,
                        telefono = ?,
                        imagen_logo = ?,
                        estado_disponibilidad = ?,
                        activo = ?,
                        id_propietario = ?
                    WHERE id_negocio = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return false;
            }

            $stmt->bind_param(
                "sssssiii",
                $nombre,
                $descripcion,
                $telefono,
                $imagen_logo,
                $estadoDisponibilidad,
                $activo,
                $idPropietario,
                $idNegocio
            );
        } else {
            $sql = "UPDATE negocios
                    SET nombre = ?,
                        descripcion = ?,
                        telefono = ?,
                        imagen_logo = ?,
                        estado_disponibilidad = ?,
                        activo = ?
                    WHERE id_negocio = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return false;
            }

            $stmt->bind_param(
                "ssss sii",
                $nombre,
                $descripcion,
                $telefono,
                $imagen_logo,
                $estadoDisponibilidad,
                $activo,
                $idNegocio
            );
        }

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
