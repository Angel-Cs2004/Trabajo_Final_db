<?php

class Producto
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obtenerTodosConDetalles(): array
    {
        $sql = "SELECT
                    p.id_producto,
                    p.nombre       AS producto,
                    p.codigo,
                    p.precio,
                    p.url_imagen,
                    p.activo,
                    p.fecha_creacion,
                    c.id_categoria,
                    c.nombre       AS categoria,
                    n.id_negocio,
                    n.nombre       AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                ORDER BY p.id_producto DESC";

        $result = $this->conn->query($sql);
        if (!$result) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorNegocio(int $idNegocio): array
    {
        $sql = "SELECT
                    p.id_producto,
                    p.nombre       AS producto,
                    p.codigo,
                    p.precio,
                    p.url_imagen,
                    p.activo,
                    p.fecha_creacion,
                    c.id_categoria,
                    c.nombre       AS categoria,
                    n.id_negocio,
                    n.nombre       AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.id_negocio = ?
                ORDER BY p.id_producto DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $idNegocio);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $data;
    }

    public function obtenerPorPropietario(int $idUsuario): array
    {
        // productos de negocios cuyo propietario es idUsuario
        $sql = "SELECT
                    p.id_producto,
                    p.nombre       AS producto,
                    p.codigo,
                    p.precio,
                    p.url_imagen,
                    p.activo,
                    p.fecha_creacion,
                    c.id_categoria,
                    c.nombre       AS categoria,
                    n.id_negocio,
                    n.nombre       AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                INNER JOIN usuarios   u ON n.id_propietario = u.id_usuario
                WHERE u.id_usuario = ?
                ORDER BY p.id_producto DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $data;
    }

    public function obtenerPorId(int $idProducto): ?array
    {
        $sql = "SELECT
                    p.id_producto,
                    p.nombre       AS producto,
                    p.codigo,
                    p.precio,
                    p.url_imagen,
                    p.activo,
                    p.fecha_creacion,
                    c.id_categoria,
                    c.nombre       AS categoria,
                    n.id_negocio,
                    n.nombre       AS negocio
                FROM productos p
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria
                INNER JOIN negocios   n ON p.id_negocio   = n.id_negocio
                WHERE p.id_producto = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return $fila ?: null;
    }

    public function crear(
        string $nombre,
        string $codigo,
        float $precio,
        ?string $url_imagen,
        int $idCategoria,
        int $idNegocio,
        int $activo = 1
    ): bool {
        $sql = "INSERT INTO productos
                (nombre, codigo, precio, url_imagen, id_categoria, id_negocio, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssdssii",
            $nombre,
            $codigo,
            $precio,
            $url_imagen,
            $idCategoria,
            $idNegocio,
            $activo
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function actualizar(
        int $idProducto,
        string $nombre,
        string $codigo,
        float $precio,
        ?string $url_imagen,
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
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssdssiii",
            $nombre,
            $codigo,
            $precio,
            $url_imagen,
            $idCategoria,
            $idNegocio,
            $activo,
            $idProducto
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
