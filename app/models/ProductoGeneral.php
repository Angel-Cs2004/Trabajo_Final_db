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

public function crearProducto(
        int $id_negocio,
        string $nombre,
        float $precio,
        ?string $url_imagen,
        string $estado,
        int $id_categoria
    ): array {
        $sql = "INSERT INTO productos
                    (nombre, precio, url_imagen, estado, id_categoria, id_negocio)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return ['ok' => false, 'msg' => 'No se pudo preparar la consulta.'];

        $stmt->bind_param("sdssii", $nombre, $precio, $url_imagen, $estado, $id_categoria, $id_negocio);

        try {
            $stmt->execute();
            $stmt->close();
            return ['ok' => true, 'msg' => 'Producto creado correctamente.'];
        } catch (mysqli_sql_exception $e) {
            $stmt->close();

            if ((int)$e->getCode() === 45000) {
                return ['ok' => false, 'msg' => $e->getMessage()];
            }

            if ((int)$e->getCode() === 1062) {
                return ['ok' => false, 'msg' => 'Ya existe un producto con ese nombre en esa tienda.'];
            }

            return ['ok' => false, 'msg' => 'Error al crear el producto.'];
        }
    }

public function editarProducto(
        int $id_producto,
        int $id_negocio,
        string $nombre,
        float $precio,
        ?string $url_imagen,
        string $estado,
        int $id_categoria
    ): array {
        $sql = "UPDATE productos
                SET nombre      = ?,
                    precio      = ?,
                    url_imagen  = ?,
                    estado      = ?,
                    id_categoria= ?,
                    id_negocio  = ?
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return ['ok' => false, 'msg' => 'No se pudo preparar la consulta.'];

        $stmt->bind_param("sdssiii", $nombre, $precio, $url_imagen, $estado, $id_categoria, $id_negocio, $id_producto);

        try {
            $stmt->execute();
            $stmt->close();
            return ['ok' => true, 'msg' => 'Producto actualizado correctamente.'];
        } catch (mysqli_sql_exception $e) {
            $stmt->close();

            if ((int)$e->getCode() === 45000) {
                return ['ok' => false, 'msg' => $e->getMessage()];
            }

            if ((int)$e->getCode() === 1062) {
                return ['ok' => false, 'msg' => 'No se puede cambiar: ya existe un producto con ese nombre en esa tienda.'];
            }

            return ['ok' => false, 'msg' => 'Error al actualizar el producto.'];
        }
    }

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
