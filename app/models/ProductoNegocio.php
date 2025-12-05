<?php

class ProductoNegocio
{
    private  $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    //OBTENER TODOS LOS PRODUCTOS DE UN NEGOCIO
    public function obtenerTodos($id_negocio)
    {
        $sql = "SELECT * FROM productos  WHERE id_negocio = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_negocio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
        $stmt->close();
        return $productos;
    }
    public function crearProducto($id_negocio, $nombre, $precio, $url_imagen, $estado, $id_categoria)
    {
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


    public function editarProducto($id_producto, $nombre, $precio, $url_imagen, $estado): bool
    {
        $sql = "UPDATE productos 
                SET nombre = ?, precio = ?, url_imagen = ?, estado = ?
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sdssi", $nombre, $precio, $url_imagen, $estado, $id_producto);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function eliminarProducto($id_producto)
    {
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $stmt->close();
    }



    
    // Aquí puedes agregar métodos para interactuar con la base de datos
}