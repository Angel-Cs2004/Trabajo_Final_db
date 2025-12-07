<?php

class Usuario
{
    private $conn; // mysqli

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene un usuario por correo usando el SP sp_obtener_usuario_login
     */
    public function obtenerPorCorreo(string $correo): ?array
    {
        $sql = "CALL sp_obtener_usuario_login(?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null; // error preparando
        }

        $stmt->bind_param('s', $correo);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            // limpiar posibles resultados extra del SP
            while ($this->conn->more_results() && $this->conn->next_result()) {;}
            return null;
        }

        $usuario = $result->fetch_assoc();
        $stmt->close();

        // limpiar el resto de resultados del SP
        while ($this->conn->more_results() && $this->conn->next_result()) {;}

        return $usuario ?: null;
    }

    /**
     * Obtiene todos los permisos del usuario según rol_tag_permiso / tags / permisos
     * y los arma en una estructura para guardar en $_SESSION['usuario_auth'].
     */
    public function obtenerPermisosPorUsuario(int $idUsuario): ?array
    {
        $sql = "
            SELECT 
                u.id_usuario,
                u.nombre        AS nombre_usuario,
                r.id_rol,
                r.nombre        AS nombre_rol,
                t.modulos       AS nombre_modulo,   -- 'usuario','negocio',...
                p.CRUD          AS codigo_permiso   -- 'CREATE','READ','UPDATE','DELETE'
            FROM usuarios u
            JOIN usuario_rol      ur  ON ur.id_usuario = u.id_usuario
            JOIN roles            r   ON r.id_rol      = ur.id_rol
            JOIN rol_tag_permiso  rtp ON rtp.id_rol    = r.id_rol
            JOIN tags             t   ON t.id_tag      = rtp.id_tag
            JOIN permisos         p   ON p.id_permiso  = rtp.id_permiso
            WHERE u.id_usuario = ?
              AND u.estado = 'activo'
              AND r.estado = 'activo'
            ORDER BY r.id_rol, t.modulos, p.CRUD
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

            $nombreRol    = $row['nombre_rol'];             // super_admin, admin_negocio...
            // Paso el módulo a MAYÚSCULAS para mantener compatibilidad:
            // 'usuario' -> 'USUARIO', 'negocio' -> 'NEGOCIO', etc.
            $codigoModulo = strtoupper($row['nombre_modulo']);
            $codigoPerm   = $row['codigo_permiso'];         // CREATE, READ, UPDATE, DELETE

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
                    $permPorModulo[$codigoModulo]['C'] = true;
                    break;
                case 'READ':
                    $permPorModulo[$codigoModulo]['R'] = true;
                    break;
                case 'UPDATE':
                    $permPorModulo[$codigoModulo]['U'] = true;
                    break;
                case 'DELETE':
                    $permPorModulo[$codigoModulo]['D'] = true;
                    break;
            }
        }

        $usuario['roles']             = $roles;
        $usuario['modulos']           = $modulos;
        $usuario['permisosPorModulo'] = $permPorModulo;

        return $usuario;
    }

    /**
     * Actualiza el hash de contraseña de un usuario
     * Se usa para migrar contraseñas planas a hasheadas
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
