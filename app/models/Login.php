<?php

class Usuario
{
    private $conn; // mysqli

    public function __construct(mysqli $conn)
    {
       
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

        $stmt->close();
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $usuario ?: null;
    }
        public function obtenerPermisosPorUsuario(int $idUsuario): ?array
    {
        $sql = "
            SELECT 
                u.id_usuario,
                u.nombre        AS nombre_usuario,
                r.id_rol,
                r.nombre        AS nombre_rol,
                t.codigo        AS codigo_modulo,   -- 'USUARIO','NEGOCIO',...
                p.codigo        AS codigo_permiso   -- 'CREATE','READ','UPDATE','DELETE'
            FROM usuarios u
            JOIN usuario_rol      ur  ON ur.id_usuario = u.id_usuario
            JOIN roles            r   ON r.id_rol      = ur.id_rol
            JOIN rol_tag_permiso  rtp ON rtp.id_rol    = r.id_rol
            JOIN tags             t   ON t.id_tag      = rtp.id_tag
            JOIN permisos         p   ON p.id_permiso  = rtp.id_permiso
            WHERE u.id_usuario = ?
              AND u.estado = 'activo'
              AND r.estado = 'activo'
            ORDER BY r.id_rol, t.codigo, p.codigo
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $usuario       = null;
        $roles         = [];
        $modulos       = [];
        $permPorModulo = [];

        while ($row = $result->fetch_assoc()) {
            if ($usuario === null) {
                // datos base del usuario (primera fila)
                $usuario = [
                    "id"               => (int)$row['id_usuario'],
                    "nombre"           => $row['nombre_usuario'],
                    "roles"            => [],
                    "modulos"          => [],
                    "permisosPorModulo"=> []
                ];
            }

            $nombreRol    = $row['nombre_rol'];
            $codigoModulo = $row['codigo_modulo'];   
            $codigoPerm   = $row['codigo_permiso'];  

            // acumular roles
            if (!in_array($nombreRol, $roles, true)) {
                $roles[] = $nombreRol;
            }

            // acumular módulos
            if (!in_array($codigoModulo, $modulos, true)) {
                $modulos[] = $codigoModulo;
            }

            if (!isset($permPorModulo[$codigoModulo])) {
                $permPorModulo[$codigoModulo] = [
                    "C" => false,
                    "R" => false,
                    "U" => false,
                    "D" => false
                ];
            }

            switch ($codigoPerm) {
                case 'CREATE':
                    $permPorModulo[$codigoModulo]['C'] = true; break;
                case 'READ':
                    $permPorModulo[$codigoModulo]['R'] = true; break;
                case 'UPDATE':
                    $permPorModulo[$codigoModulo]['U'] = true; break;
                case 'DELETE':
                    $permPorModulo[$codigoModulo]['D'] = true; break;
            }
        }

        $usuario['roles']            = $roles;
        $usuario['modulos']          = $modulos;
        $usuario['permisosPorModulo']= $permPorModulo;

        return $usuario;
    }

    /**
     * Actualiza el hash de contraseña de un usuario
     * Se usa para migrar contraseñas planas a hasheadas
     * 
     * @param int $idUsuario ID del usuario
     * @param string $nuevoHash Hash de la contraseña
     * @return bool True si se actualizó correctamente
     */
    public function actualizarPasswordHash(int $idUsuario, string $nuevoHash): bool
    {
        $sql = "UPDATE usuarios SET password_hash = ? WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('si', $nuevoHash, $idUsuario);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}
