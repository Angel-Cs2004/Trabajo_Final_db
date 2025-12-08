<?php

class Negocio
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT n.*, u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                ORDER BY n.nombre DESC";

        $result = $this->conn->query($sql);
        if (!$result) {
            return [];
        }

        $negocios = $result->fetch_all(MYSQLI_ASSOC);

        // Calculamos disponibilidad en PHP
        $ahora = new DateTime('now', new DateTimeZone('America/Lima'));
        $horaActual = $ahora->format('H:i:s');

        foreach ($negocios as &$negocio) {
            $estadoBD      = $negocio['estado'] ?? 'inactivo';
            $horaApertura  = $negocio['hora_apertura'] ?? '00:00:00';
            $horaCierre    = $negocio['hora_cierre'] ?? '00:00:00';

            if (
                $estadoBD === 'activo' &&
                $horaApertura <= $horaActual &&
                $horaCierre   > $horaActual
            ) {
                $negocio['estado_disponibilidad'] = 'abierto';
            } else {
                $negocio['estado_disponibilidad'] = 'cerrado';
            }
        }
        unset($negocio); // por seguridad de referencia

        return $negocios;
    }




    public function obtenerPorPropietario(int $idPropietario): array
    {
        $sql = "SELECT n.*, u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                WHERE  n.id_propietario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idPropietario);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            return [];
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function obtenerPorId(int $idNegocio): ?array
    {
        $sql = "SELECT n.*, u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                WHERE n.id_negocio = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $idNegocio);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            return null;
        }
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function crear(
        string $nombre,
        string $descripcion,
        ?string $imagen_logo,
        string $estado,
        string $hora_apertura,
        string $hora_cierre,
        int $idPropietario
    ): bool {

        $sql = "INSERT INTO negocios 
                (nombre, descripcion, estado, imagen_logo, hora_apertura, hora_cierre, id_propietario)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            'ssssssi',
            $nombre,
            $descripcion,
            $estado,        // 'activo' / 'inactivo'
            $imagen_logo,   // puede ser null
            $hora_apertura, // '09:00:00'
            $hora_cierre,   // '18:00:00'
            $idPropietario
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

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

        if ($idPropietario !== null) {
            // Admin / super_admin puede cambiar propietario
            $sql = "UPDATE negocios
                    SET nombre = ?,
                        descripcion = ?,
                        estado = ?,
                        imagen_logo = ?,
                        hora_apertura = ?,
                        hora_cierre = ?,
                        id_propietario = ?
                    WHERE id_negocio = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) return false;

            $stmt->bind_param(
                'ssssssii',
                $nombre,
                $descripcion,
                $estado,
                $imagen_logo,
                $hora_apertura,
                $hora_cierre,
                $idPropietario,
                $idNegocio
            );

        } else {
            // Propietario normal: no cambia id_propietario
            $sql = "UPDATE negocios
                    SET nombre = ?,
                        descripcion = ?,
                        estado = ?,
                        imagen_logo = ?,
                        hora_apertura = ?,
                        hora_cierre = ?
                    WHERE id_negocio = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) return false;

            $stmt->bind_param(
                'ssssssi',
                $nombre,
                $descripcion,
                $estado,
                $imagen_logo,
                $hora_apertura,
                $hora_cierre,
                $idNegocio
            );
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
