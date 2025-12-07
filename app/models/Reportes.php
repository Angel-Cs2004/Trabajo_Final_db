<?php

class Reportes
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // CALL sp_reporte_productos_por_negocio(p_id_negocio)
    public function productosPorNegocio(int $idNegocio): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_negocio(?)");
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idNegocio);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $data;
    }

    // CALL sp_reporte_productos_por_categoria(p_id_categoria)
    public function productosPorCategoria(int $idCategoria): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_categoria(?)");
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $data;
    }

    // CALL sp_reporte_productos_rango_precio(p_precio_min, p_precio_max, p_id_negocio)
    public function productosRangoPrecio(float $min, float $max, int $idNegocio = 0): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_productos_rango_precio(?, ?, ?)");
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('ddi', $min, $max, $idNegocio);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $data;
    }

    // CALL sp_reporte_negocios_por_propietario(p_id_propietario)
    public function negociosPorPropietario(int $idPropietario): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_negocios_por_propietario(?)");
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idPropietario);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $data;
    }
}
