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
                WHERE n.activo = 1
                ORDER BY n.fecha_creacion DESC";

        $result = $this->conn->query($sql);
        if (!$result) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorPropietario(int $idPropietario): array
    {
        $sql = "SELECT n.*, u.nombre AS propietario
                FROM negocios n
                INNER JOIN usuarios u ON n.id_propietario = u.id_usuario
                WHERE n.activo = 1
                  AND n.id_propietario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idPropietario);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
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
            return null;
        }
        $row = $result->fetch_assoc();
        return $row ?: null;
    }

    public function crear(
        string $nombre,
        string $descripcion,
        string $telefono,
        ?string $imagen_logo,
        string $estado_disponibilidad,
        int $activo,
        int $idPropietario
    ): bool {

        $sql = "INSERT INTO negocios 
                (nombre, descripcion, telefono, imagen_logo, estado_disponibilidad, activo, id_propietario)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            'ssssssi',  
            $nombre,
            $descripcion,
            $telefono,
            $imagen_logo,
            $estado_disponibilidad,
            $activo,
            $idPropietario
        );

        return $stmt->execute();
    }

    public function actualizar(
        int $idNegocio,
        string $nombre,
        string $descripcion,
        string $telefono,
        ?string $imagen_logo,
        string $estado_disponibilidad,
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
            if (!$stmt) return false;

            
            $stmt->bind_param(
                'sssssiii',
                $nombre,                
                $descripcion,           
                $telefono,              
                $imagen_logo,           
                $estado_disponibilidad, 
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
            if (!$stmt) return false;

           
            $stmt->bind_param(
                'sssssii',
                $nombre,                
                $descripcion,           
                $telefono,              
                $imagen_logo,           
                $estado_disponibilidad, 
                $activo,                
                $idNegocio              
            );
        }

        return $stmt->execute();
    }


}
