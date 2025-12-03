<?php

class Usuarios
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtener usuario por correo usando el SP sp_obtener_usuario_login
     */
    public function obtenerPorCorreo(string $correo): ?array
    {
        $correo = trim($correo);

        $sql = "CALL sp_obtener_usuario_login(?)";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $stmt->close();
            while ($this->conn->more_results() && $this->conn->next_result()) {;}

            if ($resultado && $resultado->num_rows > 0) {
                return $resultado->fetch_assoc();
            }
        }

        return null;
    }

    /**
     * Listar todos los usuarios con rol
     */
    public function obtenerTodos(): array
    {
        $sql = "
            SELECT 
                u.id_usuario,
                u.nombre,
                u.correo,
                u.identificacion,
                u.telefono,
                u.estado,
                r.nombre AS rol
            FROM usuarios u
            LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
            LEFT JOIN roles r ON ur.id_rol = r.id_rol
            ORDER BY u.id_usuario DESC
        ";

        $resultado = $this->conn->query($sql);
        $usuarios = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
        }

        return $usuarios;
    }

    /**
     * Obtener roles disponibles
     */
    public function obtenerRoles(): array
    {
        $sql = "SELECT id_rol, nombre FROM roles ORDER BY nombre ASC";
        $resultado = $this->conn->query($sql);

        $roles = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $roles[] = $fila;
            }
        }

        return $roles;
    }

    /**
     * Crear usuario + asignar rol
     */
    public function crear(
        string $nombre,
        string $correo,
        ?string $identificacion,
        ?string $telefono,
        string $password,
        string $estado,
        int $idRol
    ): bool {

        // Insert usuario
        $sqlUsuario = "
            INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sqlUsuario);
        $stmt->bind_param("ssssss", $nombre, $correo, $identificacion, $telefono, $password, $estado);
        $stmt->execute();

        $idUsuario = $stmt->insert_id;
        $stmt->close();

        // Insert relación usuario - rol
        $sqlRol = "
            INSERT INTO usuario_rol (id_usuario, id_rol)
            VALUES (?, ?)
        ";

        $stmt2 = $this->conn->prepare($sqlRol);
        $stmt2->bind_param("ii", $idUsuario, $idRol);
        $stmt2->execute();
        $stmt2->close();

        return true;
    }

    /**
     * Obtener un usuario por su ID
     */
    public function obtenerPorId(int $id): ?array
    {
        $sql = "
            SELECT 
                u.id_usuario,
                u.nombre,
                u.correo,
                u.identificacion,
                u.telefono,
                u.estado,
                r.id_rol,
                r.nombre AS rol
            FROM usuarios u
            LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
            LEFT JOIN roles r ON ur.id_rol = r.id_rol
            WHERE u.id_usuario = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $stmt->close();

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }

        return null;
    }

    /**
     * Actualizar usuario con clave
     */
    public function actualizar(
        int $id,
        string $nombre,
        string $correo,
        ?string $identificacion,
        ?string $telefono,
        string $password,
        string $estado,
        int $idRol
    ): bool {

        // Update usuario
        $sql = "
            UPDATE usuarios
            SET nombre = ?, correo = ?, identificacion = ?, telefono = ?, password_hash = ?, estado = ?
            WHERE id_usuario = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre, $correo, $identificacion, $telefono, $password, $estado, $id);
        $stmt->execute();
        $stmt->close();

        // Update rol
        $sqlRol = "
            UPDATE usuario_rol
            SET id_rol = ?
            WHERE id_usuario = ?
        ";

        $stmt2 = $this->conn->prepare($sqlRol);
        $stmt2->bind_param("ii", $idRol, $id);
        $stmt2->execute();
        $stmt2->close();

        return true;
    }

    /**
     * Actualizar usuario SIN cambiar clave
     */
    public function actualizarSinClave(
        int $id,
        string $nombre,
        string $correo,
        ?string $identificacion,
        ?string $telefono,
        string $estado,
        int $idRol
    ): bool {

        // Update usuario sin password
        $sql = "
            UPDATE usuarios
            SET nombre = ?, correo = ?, identificacion = ?, telefono = ?, estado = ?
            WHERE id_usuario = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $correo, $identificacion, $telefono, $estado, $id);
        $stmt->execute();
        $stmt->close();

        // Update rol
        $sqlRol = "
            UPDATE usuario_rol
            SET id_rol = ?
            WHERE id_usuario = ?
        ";

        $stmt2 = $this->conn->prepare($sqlRol);
        $stmt2->bind_param("ii", $idRol, $id);
        $stmt2->execute();
        $stmt2->close();

        return true;
    }

    /**
 * Obtener todos los roles
 */

    /**
     * Convertir nombre de rol → id_rol
     */
    public function obtenerIdRolPorNombre(string $nombre)
    {
        $sql = "SELECT id_rol FROM roles WHERE nombre = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception("No existe el rol '$nombre' en la base de datos");
        }

        return intval($row['id_rol']);
    }

}
