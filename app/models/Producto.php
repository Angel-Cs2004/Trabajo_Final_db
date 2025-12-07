<?php


class Producto
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }


    public function obtenerTodos(): array
    {
        $sql = "SELECT 
                    id_producto,
                    nombre,
                    precio,
                    url_imagen,
                    estado
                FROM productos
                ORDER BY nombre ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function crearProducto($nombre, $precio, $url_imagen, $estado)
    {
        $sql = "INSERT INTO productos (nombre, precio, url_imagen, estado) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdss", $nombre, $precio, $url_imagen, $estado);
        $stmt->execute();
        $stmt->close();
    }



    // Otros métodos relacionados con productos pueden ir aquí
}
