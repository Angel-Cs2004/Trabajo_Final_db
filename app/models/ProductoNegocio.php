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
        $sql = "SELECT 
                    p.*, 
                    c.nombre AS nombre_categoria, 
                    n.id_negocio,
                    n.nombre AS nombre_negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE n.id_propietario = ?
                ORDER BY n.nombre ASC, p.nombre ASC";   

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idUsuarioPropietario);
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
): array {
    $sql = "INSERT INTO productos 
                (nombre, precio, url_imagen, estado, id_categoria, id_negocio)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        return ['ok' => false, 'msg' => 'No se pudo preparar la consulta.'];
    }

    $stmt->bind_param(
        "sdssii",
        $nombre,
        $precio,
        $url_imagen,
        $estado,
        $id_categoria,
        $id_negocio
    );

    try {
        $stmt->execute();
        $stmt->close();
        return ['ok' => true, 'msg' => 'Producto creado correctamente.'];
    } catch (mysqli_sql_exception $e) {
        $stmt->close();

        // 45000 = SIGNAL del trigger (duplicado u otras validaciones de negocio)
        if ((int)$e->getCode() === 45000) {
            return ['ok' => false, 'msg' => $e->getMessage()];
        }

        // 1062 = Duplicate entry (si un día usas UNIQUE en vez de trigger)
        if ((int)$e->getCode() === 1062) {
            return ['ok' => false, 'msg' => 'Ya existe un producto con ese nombre en esta tienda.'];
        }

        return ['ok' => false, 'msg' => 'No se puede crear el producto. Ya existe un producto similar en tu tienda.'];
    }
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
): array {
    $sql = "UPDATE productos 
            SET nombre = ?, 
                precio = ?, 
                url_imagen = ?, 
                estado = ?,
                id_categoria = ?
            WHERE id_producto = ?";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        return ['ok' => false, 'msg' => 'No se pudo preparar la consulta.'];
    }

    $stmt->bind_param("sdssii", $nombre, $precio, $url_imagen, $estado, $id_categoria, $id_producto);

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
            return ['ok' => false, 'msg' => 'No se puede cambiar el nombre: ya existe un producto con ese nombre en esta tienda.'];
        }

        return ['ok' => false, 'msg' => 'No es posible cambiar el nombre, Ya existe un producto similar en tu tienda.'];
    }
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
    public function crearDesdeImport(
    string $nombre,
    float $precio,
    int $idCategoria,
    ?string $urlImagen,
    string $estado,
    int $idNegocio
): bool {
    $sql = "INSERT INTO productos_negocio
            (nombre, precio, id_categoria, url_imagen, estado, id_negocio)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param(
        'sdissi',
        $nombre,
        $precio,
        $idCategoria,
        $urlImagen,
        $estado,
        $idNegocio
    );

    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

}
