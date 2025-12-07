<?php

require_once __DIR__ . '/../models/Login.php'; // Dentro está la clase Usuario

class AuthController
{
    private $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function login()
    {
        session_start();
        $mensajeError = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $correo   = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($correo === '' || $password === '') {
                $mensajeError = "Debes completar correo y contraseña.";
            } else {

                $modeloUsuario = new Usuario($this->conn);
                $usuarioBD = $modeloUsuario->obtenerPorCorreo($correo);

                if (!$usuarioBD) {
                    $mensajeError = "Usuario o contraseña incorrectos.";
                } else {

                    // OJO: aquí aún estás comparando en texto plano
                    // Luego lo cambiamos a password_verify cuando migres los hashes.
                    if ($password === $usuarioBD['password_hash']) {

                        $_SESSION['id_usuario'] = (int)$usuarioBD['id_usuario'];
                        $_SESSION['nombre']     = $usuarioBD['nombre'];
                        $_SESSION['correo']     = $usuarioBD['correo'];
                        $_SESSION['rol']        = $usuarioBD['rol']; // viene de sp_obtener_usuario_login

                        // Construir estructura de permisos desde la nueva BD
                        $estructuraPermisos = $modeloUsuario->obtenerPermisosPorUsuario((int)$usuarioBD['id_usuario']);

                        // Si por alguna razón el usuario no tiene permisos configurados,
                        // al menos guardamos algo coherente.
                        if ($estructuraPermisos === null) {
                            $estructuraPermisos = [
                                "id"               => (int)$usuarioBD['id_usuario'],
                                "nombre"           => $usuarioBD['nombre'],
                                "roles"            => [$usuarioBD['rol']],
                                "modulos"          => [],
                                "permisosPorModulo"=> []
                            ];
                        }

                        $_SESSION['usuario_auth'] = $estructuraPermisos;

                        // Redirección según rol principal
                        // super_admin y admin_negocio -> dashboardAdmin
                        // resto -> dashboardProveedor
                        if (in_array($_SESSION['rol'], ['super_admin', 'admin_negocio'], true)) {
                            header("Location: index.php?c=home&a=dashboardAdmin");
                        } else {
                            header("Location: index.php?c=home&a=dashboardProveedor");
                        }
                        exit;

                    } else {
                        $mensajeError = "Usuario o contraseña incorrectos.";
                    }
                }
            }
        }

        require __DIR__ . '/../views/home/login.php';
    }
}
