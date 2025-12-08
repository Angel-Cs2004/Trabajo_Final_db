<?php

class Permisos
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT id_permiso, nombre, CRUD
                FROM permisos
                ORDER BY id_permiso ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
