<?php

class Producto
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Todos los productos activos con su categoría y negocio
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria,
                    n.nombre AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.activo = 1
                ORDER BY p.fecha_creacion DESC, p.id_producto DESC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Productos por negocio
     */
    public function obtenerPorNegocio(int $idNegocio): array
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria,
                    n.nombre AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.activo = 1
                  AND p.id_negocio = ?
                ORDER BY p.fecha_creacion DESC, p.id_producto DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $idNegocio);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Productos de todos los negocios de un propietario
     */
    public function obtenerPorPropietario(int $idPropietario): array
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria,
                    n.nombre AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.activo = 1
                  AND n.id_propietario = ?
                ORDER BY p.fecha_creacion DESC, p.id_producto DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $idPropietario);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Producto por ID
     */
    public function obtenerPorId(int $idProducto): ?array
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria,
                    n.nombre AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.id_producto = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $row ?: null;
    }

    /**
     * Crear producto usando sp_insertar_producto
     */
    public function crear(
        string $nombre,
        string $codigo,
        float $precio,
        ?string $urlImagen,
        int $idCategoria,
        int $idNegocio
    ): bool {

        $sql = "CALL sp_insertar_producto(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssdssi",
            $nombre,
            $codigo,
            $precio,
            $urlImagen,
            $idCategoria,
            $idNegocio
        );

        $ok = $stmt->execute();
        $stmt->close();

        // limpiar resultados múltiples de la SP
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $ok;
    }

    /**
     * Actualizar producto (sin SP)
     */
    public function actualizar(
        int $idProducto,
        string $nombre,
        string $codigo,
        float $precio,
        ?string $urlImagen,
        int $idCategoria,
        int $idNegocio,
        int $activo
    ): bool {

        $sql = "UPDATE productos
                SET nombre = ?,
                    codigo = ?,
                    precio = ?,
                    url_imagen = ?,
                    id_categoria = ?,
                    id_negocio = ?,
                    activo = ?
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssdssiii",
            $nombre,
            $codigo,
            $precio,
            $urlImagen,
            $idCategoria,
            $idNegocio,
            $activo,
            $idProducto
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function desactivar(int $idProducto): bool
    {
        $sql = "UPDATE productos SET activo = 0 WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $idProducto);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
