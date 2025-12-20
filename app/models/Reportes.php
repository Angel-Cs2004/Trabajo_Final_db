<?php

class Reportes
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    private function limpiarSP(mysqli_stmt $stmt): void
    {
        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}
    }

    // =========================
    // REPORTES EXISTENTES (ya tenías)
    // =========================

    public function productosPorNegocio(int $idNegocio): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_negocio(?)");
        if (!$stmt) return [];

        $stmt->bind_param('i', $idNegocio);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function productosPorCategoria(int $idCategoria): array
{
    // 0 => todas las categorías
    if ($idCategoria <= 0) {
        $sql = "
            SELECT 
                c.id_categoria,
                c.nombre AS categoria,
                p.id_producto,
                p.nombre AS producto,
                p.precio,
                n.nombre AS negocio,
                p.estado
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            INNER JOIN negocios n   ON p.id_negocio   = n.id_negocio
            WHERE p.estado = 'activo'
            ORDER BY c.nombre ASC, p.nombre ASC
        ";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Si hay categoría => SP
    $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_categoria(?)");
    if (!$stmt) return [];

    $stmt->bind_param('i', $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    $stmt->close();
    while ($this->conn->more_results() && $this->conn->next_result()) {;}

    return $data;
}



    public function productosRangoPrecio(float $min, float $max, int $idNegocio = 0): array
{
    if ($min < 0) $min = 0;

    // Si max viene 0 => mostrar todo (hasta un valor grande)
    if ($max <= 0) $max = 999999;

    if ($max < $min) return [];

    $stmt = $this->conn->prepare("CALL sp_reporte_productos_rango_precio(?, ?, ?)");
    if (!$stmt) return [];

    $stmt->bind_param('ddi', $min, $max, $idNegocio);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    $stmt->close();
    while ($this->conn->more_results() && $this->conn->next_result()) {;}

    return $data;
}



    public function negociosPorPropietario(int $idPropietario): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_negocios_por_propietario(?)");
        if (!$stmt) return [];

        $stmt->bind_param('i', $idPropietario);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    // =========================
    // 3 REPORTES NUEVOS
    // =========================

    public function usuariosConRol(int $idRol = 0, string $estado = 'todos'): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_usuarios_roles(?, ?)");
        if (!$stmt) return [];

        $stmt->bind_param('is', $idRol, $estado);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function rolesPermisos(int $idRol = 0, string $tag = 'todos'): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_roles_permisos(?, ?)");
        if (!$stmt) return [];

        $stmt->bind_param('is', $idRol, $tag);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function resumenTiendas(): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_resumen_tiendas()");
        if (!$stmt) return [];

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    // =========================
    // LISTAS PARA FILTROS (combos)
    // =========================

    public function listarCategorias(): array
    {
        $sql = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function listarNegocios(): array
    {
        $sql = "SELECT id_negocio, nombre FROM negocios ORDER BY nombre ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function listarRoles(): array
    {
        $sql = "SELECT id_rol, nombre FROM roles ORDER BY nombre ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function listarTags(): array
    {
        $sql = "SELECT modulos FROM tags ORDER BY modulos ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}
