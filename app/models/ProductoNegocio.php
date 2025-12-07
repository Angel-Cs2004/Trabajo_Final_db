<?php

class ProductoNegocio
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene todos los productos de todos los negocios
     * pertenecientes a un usuario propietario.
     *
     * @param int $idUsuarioPropietario
     * @return array
     */
    public function obtenerTodos(int $idUsuarioPropietario): array
    {
        $sql = "SELECT p.*, c.nombre AS nombre_categoria, n.nombre AS nombre_negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE n.id_propietario = ?
                ORDER BY p.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $idUsuarioPropietario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $productos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $productos;
    }

    /**
     * Crea un producto asociado a un negocio
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
     * Obtener un producto por ID
     */
    public function obtenerPorId(int $id_producto): ?array
    {
        $sql = "SELECT p.*, c.nombre AS nombre_categoria, n.nombre AS nombre_negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.id_producto = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dato = $resultado ? $resultado->fetch_assoc() : null;
        $stmt->close();

        return $dato ?: null;
    }

    /**
     * Edita un producto (incluyendo cambiar la categoría)
     */
    public function editarProducto(
        int $id_producto,
        string $nombre,
        float $precio,
        ?string $url_imagen,
        string $estado,
        int $id_categoria
    ): bool {
        $sql = "UPDATE productos 
                SET nombre = ?, 
                    precio = ?, 
                    url_imagen = ?, 
                    estado = ?,
                    id_categoria = ?
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sdssii",
            $nombre,
            $precio,
            $url_imagen,
            $estado,
            $id_categoria,
            $id_producto
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function eliminarProducto(int $id_producto): bool
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
