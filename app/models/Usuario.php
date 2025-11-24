<?php

class Usuario
{
    private $conn; // mysqli

    public function __construct(mysqli $conn)
    {
        //conectamos con la base de datos
        $this->conn = $conn;
    }

    public function obtenerPorCorreo(string $correo): ?array
    {
        //el ? es como un placeholder
        $sql = "CALL sp_obtener_usuario_login(?)";
        //Cabe aclarar que "sp_obtener_usuario_login(?)"
        // esta definida en bd_trabajo_final
        
        // Se prepara la sentencias con el metodo "prepare"
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null; // error preparando
        }

        // Inyecto el parametro  usuario en  sql = "?" 
        $stmt->bind_param('s', $correo);
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->get_result();
        if (!$result) {
            return null;
        }

        // TE DEVUELVE UN ARRAY ASOCIATIVO ==>
        // $usuarios === $usuario["name de columna"] : valor
        //$usuario['nombre']
        //$usuario['correo']
        //$usuario['password_hash']
        $usuario = $result->fetch_assoc();

        // Limpieza por llamadas a SP en mysqli
        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $usuario ?: null;
    }
}
