<?php

class Reportes
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // =========================
    // UTILIDAD PARA STORED PROCEDURES
    // =========================
    private function limpiarSP(mysqli_stmt $stmt): void
    {
        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}
    }

    // =====================================================
    // REPORTE GENERAL
    // =====================================================

    public function productosPorCategoria(int $idCategoria): array
    {
        if ($idCategoria <= 0) {
            $sql = "
                SELECT 
                    c.nombre AS categoria,
                    p.nombre AS producto,
                    p.precio,
                    n.nombre AS negocio,
                    p.estado
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios n   ON p.id_negocio   = n.id_negocio
                WHERE p.estado = 'activo'
                ORDER BY c.nombre, p.nombre
            ";
            $res = $this->conn->query($sql);
            return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        }

        $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_categoria(?)");
        if (!$stmt) return [];

        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function productosRangoPrecio(float $min, float $max, int $idNegocio = 0): array
    {
        if ($min < 0) $min = 0;
        if ($max <= 0) $max = 999999;
        if ($max < $min) return [];

        $stmt = $this->conn->prepare("CALL sp_reporte_productos_rango_precio(?, ?, ?)");
        if (!$stmt) return [];

        $stmt->bind_param('ddi', $min, $max, $idNegocio);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    // =====================================================
    // REPORTES ADMIN
    // =====================================================

    public function usuariosConRol(int $idRol = 0, string $estado = 'todos'): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_usuarios_roles(?, ?)");
        if (!$stmt) return [];

        $stmt->bind_param('is', $idRol, $estado);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function rolesPermisos(int $idRol = 0, string $tag = 'todos'): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_roles_permisos(?, ?)");
        if (!$stmt) return [];

        $stmt->bind_param('is', $idRol, $tag);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function resumenTiendas(): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_resumen_tiendas()");
        if (!$stmt) return [];

        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    // =====================================================
    // LISTAS PARA COMBOS
    // =====================================================

    public function listarCategorias(): array
    {
        return $this->conn->query("SELECT id_categoria, nombre FROM categorias ORDER BY nombre")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function listarNegocios(): array
    {
        return $this->conn->query("SELECT id_negocio, nombre FROM negocios ORDER BY nombre")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function listarRoles(): array
    {
        return $this->conn->query("SELECT id_rol, nombre FROM roles ORDER BY nombre")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function listarTags(): array
    {
        return $this->conn->query("SELECT modulos FROM tags ORDER BY modulos")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function listarPropietariosConNegocio(): array
    {
        $sql = "
            SELECT DISTINCT u.id_usuario, u.nombre
            FROM usuarios u
            INNER JOIN negocios n ON n.id_propietario = u.id_usuario
            ORDER BY u.nombre
        ";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // =====================================================
    // DETALLE DE TIENDA (ADMIN)
    // =====================================================

    public function negociosPorPropietario(int $idPropietario): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_negocios_por_propietario(?)");
        $stmt->bind_param("i", $idPropietario);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    public function detalleNegocio(int $idNegocio): ?array
    {
        $stmt = $this->conn->prepare("CALL sp_negocios_obtener_por_id(?)");
        $stmt->bind_param("i", $idNegocio);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

        $this->limpiarSP($stmt);
        return $row;
    }

    public function productosPorNegocio(int $idNegocio): array
    {
        $stmt = $this->conn->prepare("CALL sp_reporte_productos_por_negocio(?)");
        $stmt->bind_param("i", $idNegocio);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $this->limpiarSP($stmt);
        return $data;
    }

    // =====================================================
    // REPORTES "MIOS" (PROPIETARIO)
    // =====================================================

    public function listarMisNegocios(int $idUsuario): array
    {
        $stmt = $this->conn->prepare("
            SELECT id_negocio, nombre
            FROM negocios
            WHERE id_propietario = ?
            ORDER BY nombre
        ");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $data;
    }

    public function misNegocios(int $idUsuario, string $estado, string $busqueda): array
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM negocios
            WHERE id_propietario = ?
              AND (? = 'todos' OR estado = ?)
              AND (? = '' OR nombre LIKE CONCAT('%', ?, '%'))
            ORDER BY nombre
        ");
        $stmt->bind_param("issss", $idUsuario, $estado, $estado, $busqueda, $busqueda);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $data;
    }

    public function misProductos(int $idUsuario, int $idNegocio): array
    {
        $stmt = $this->conn->prepare("
            SELECT n.nombre AS negocio, c.nombre AS categoria,
                   p.nombre AS producto, p.precio, p.estado
            FROM productos p
            INNER JOIN negocios n ON n.id_negocio = p.id_negocio
            INNER JOIN categorias c ON c.id_categoria = p.id_categoria
            WHERE n.id_propietario = ?
              AND (? = 0 OR n.id_negocio = ?)
            ORDER BY n.nombre, c.nombre, p.nombre
        ");
        $stmt->bind_param("iii", $idUsuario, $idNegocio, $idNegocio);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $data;
    }

    public function detalleNegocioMio(int $idUsuario, int $idNegocio): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM negocios
            WHERE id_negocio = ? AND id_propietario = ?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $idNegocio, $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;
        $stmt->close();

        return $row;
    }

    public function productosPorNegocioMio(int $idUsuario, int $idNegocio): array
    {
        $stmt = $this->conn->prepare("
            SELECT c.nombre AS categoria, p.nombre AS producto,
                   p.precio, p.estado
            FROM productos p
            INNER JOIN categorias c ON c.id_categoria = p.id_categoria
            INNER JOIN negocios n ON n.id_negocio = p.id_negocio
            WHERE n.id_propietario = ? AND n.id_negocio = ?
            ORDER BY c.nombre, p.nombre
        ");
        $stmt->bind_param("ii", $idUsuario, $idNegocio);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $data;
    }

    // =====================================================
    // UTILIDADES
    // =====================================================

    public function calcularDisponibilidad(string $estado, $apertura, $cierre): string
    {
        if ($estado !== 'activo' || !$apertura || !$cierre) return 'cerrado';
        $now = date('H:i:s');
        return ($apertura <= $now && $now < $cierre) ? 'abierto' : 'cerrado';
    }

    public function resumenPreciosProductos(array $productos): array
    {
        $precios = array_map(fn($p) => (float)$p['precio'], $productos);
        if (!$precios) return [
            'total_productos' => 0,
            'precio_min' => 0,
            'precio_max' => 0,
            'precio_promedio' => 0,
        ];

        return [
            'total_productos' => count($precios),
            'precio_min' => min($precios),
            'precio_max' => max($precios),
            'precio_promedio' => number_format(array_sum($precios) / count($precios), 2),
        ];
    }
    // =====================================================
    // TABLEROS (listas completas)
    // =====================================================

    public function categoriasTablero(): array
    {
        $sql = "SELECT id_categoria, nombre, descripcion, estado
                FROM categorias
                ORDER BY nombre ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function parametrosTablero(): array
    {
        $sql = "SELECT id_parametro_imagen, nombre, etiqueta, alto_px, ancho_px, categoria, formatos_validos
                FROM parametros_imagenes
                ORDER BY nombre ASC";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

}
