<?php

class ProductoGeneral
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Lista TODOS los productos de TODOS los negocios
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre  AS nombre_categoria,
                    n.nombre  AS nombre_negocio,
                    u.nombre  AS propietario
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                INNER JOIN usuarios   u ON n.id_propietario = u.id_usuario
                ORDER BY n.nombre ASC, p.nombre ASC";

        $result = $this->conn->query($sql);
        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener un producto por ID (modo general)
     */
    public function obtenerPorId(int $id_producto): ?array
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre  AS nombre_categoria,
                    n.nombre  AS nombre_negocio,
                    u.nombre  AS propietario
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                INNER JOIN usuarios   u ON n.id_propietario = u.id_usuario
                WHERE p.id_producto = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $res = $stmt->get_result();
        $dato = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return $dato ?: null;
    }

    /**
     * Crear producto (modo general, se indica explícitamente el negocio)
     */
    public function crearProducto(
        int $id_negocio,
        string $nombre,
        float $precio,
        ?string $url_imagen,
        string $estado,
        int $id_categoria
    ): bool {
        $sql = "INSERT INTO productos
                    (nombre, precio, url_imagen, estado, id_categoria, id_negocio)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "sdssii",
            $nombre,
            $precio,
            $url_imagen,
            $estado,
            $id_categoria,
            $id_negocio
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    /**
     * Actualizar producto (modo general)
     */
    public function editarProducto(
        int $id_producto,
        int $id_negocio,
        string $nombre,
        float $precio,
        ?string $url_imagen,
        string $estado,
        int $id_categoria
    ): bool {
        $sql = "UPDATE productos
                SET nombre      = ?,
                    precio      = ?,
                    url_imagen  = ?,
                    estado      = ?,
                    id_categoria= ?,
                    id_negocio  = ?
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "sdssiii",
            $nombre,
            $precio,
            $url_imagen,
            $estado,
            $id_categoria,
            $id_negocio,
            $id_producto
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    /**
     * Eliminar producto
     */
    public function eliminar(int $id_producto): bool
    {
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_producto);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
