<?php

require_once __DIR__ . '/../models/Login.php';

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

                    if ($password === $usuarioBD['password_hash']) {

                        $_SESSION['id_usuario'] = $usuarioBD['id_usuario'];
                        $_SESSION['nombre']     = $usuarioBD['nombre'];
                        $_SESSION['correo']     = $usuarioBD['correo'];
                        $_SESSION['rol']        = $usuarioBD['rol'];
                        $estructuraPermisos = $modeloUsuario->obtenerPermisosPorUsuario($usuarioBD['id_usuario']);

                        // Guardarla en sesión
                        $_SESSION['usuario_auth'] = $estructuraPermisos;

                        if ($_SESSION['rol'] === "admin" || $_SESSION['rol'] === "super_admin") {
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
