<?php

class Usuario
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Obtener usuario por correo 
    public function obtenerPorCorreo(string $correo): ?array
    {
        $sql = "SELECT 
                    u.id_usuario,
                    u.nombre,
                    u.correo,
                    u.identificacion,
                    u.telefono,
                    u.password_hash,
                    u.estado,
                    r.nombre AS rol
                FROM usuarios u
                LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.correo = ?
                  AND u.estado = 'activo'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        return $usuario ?: null;
    }

    // Obtener todos los usuarios
    public function obtenerTodos(): array
    {
        $sql = "SELECT 
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
                ORDER BY u.nombre ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener usuario por ID
    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT 
                    u.*,
                    r.nombre AS rol
                FROM usuarios u
                LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.id_usuario = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        return $usuario ?: null;
    }

    // Crear nuevo usuario
    public function crear($nombre, $correo, $identificacion, $telefono, $password, $estado, $rol)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO usuarios (nombre, correo, identificacion, telefono, password_hash, estado)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssss", $nombre, $correo, $identificacion, $telefono, $password, $estado);
        $stmt->execute();

        $idUsuario = $stmt->insert_id;

        $sql = "INSERT INTO usuario_rol (id_usuario, id_rol)
                VALUES (?, (SELECT id_rol FROM roles WHERE nombre = ?))";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $idUsuario, $rol);
        $stmt->execute();
    }

    // Actualizar usuario (con clave)
    public function actualizar($id, $nombre, $correo, $identificacion, $telefono, $password, $estado, $rol)
    {
        $stmt = $this->conn->prepare(
            "UPDATE usuarios 
             SET nombre=?, correo=?, identificacion=?, telefono=?, password_hash=?, estado=?
             WHERE id_usuario=?"
        );
        $stmt->bind_param("ssssssi", $nombre, $correo, $identificacion, $telefono, $password, $estado, $id);
        $stmt->execute();

        $stmt = $this->conn->prepare(
            "UPDATE usuario_rol 
             SET id_rol = (SELECT id_rol FROM roles WHERE nombre = ?)
             WHERE id_usuario = ?"
        );
        $stmt->bind_param("si", $rol, $id);
        $stmt->execute();
    }

    // Actualizar usuario (sin cambiar clave)
    public function actualizarSinClave($id, $nombre, $correo, $identificacion, $telefono, $estado, $rol)
    {
        $stmt = $this->conn->prepare(
            "UPDATE usuarios 
             SET nombre=?, correo=?, identificacion=?, telefono=?, estado=?
             WHERE id_usuario=?"
        );
        $stmt->bind_param("sssssi", $nombre, $correo, $identificacion, $telefono, $estado, $id);
        $stmt->execute();

        $stmt = $this->conn->prepare(
            "UPDATE usuario_rol 
             SET id_rol = (SELECT id_rol FROM roles WHERE nombre = ?)
             WHERE id_usuario = ?"
        );
        $stmt->bind_param("si", $rol, $id);
        $stmt->execute();
    }

    public function obtenerRoles(): array
    {
        $sql = "SELECT id_rol, nombre FROM roles ORDER BY nombre ASC";
        $result = $this->conn->query($sql);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    
}
