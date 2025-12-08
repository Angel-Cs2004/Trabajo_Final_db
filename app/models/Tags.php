<?php

class Tags
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT id_tag, modulos
                FROM tags
                ORDER BY modulos ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
