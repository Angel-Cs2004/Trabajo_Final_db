
<?php
// EN RESUMEN LO QUE ES HACE ES PODER RESIVIER LA SOLICITUD
//LUEGO IDETIFICAR ENTRE SI ES PSOT O GET
// UTLIZAR OS PARAMETROS UQE SE MANDO CON EL SUBMIT 
// SEGUN ESOS PARAMWTROS PODER VERIFICAR LA SESION 
require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private $conn; // mysqli

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function login()
    {
        // verifica si ya se inicio antes y en ese caso solo rellenarlo
        session_start();
        $mensajeError = "";

        // SERVER == VARIABLE LOCAL === ARRAY ASOCIATIVO
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
            $correo   = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($correo === '' || $password === '') {
                $mensajeError = "Debes completar correo y contraseña.";
            } else {

                // se puede decir que separamos memeoria 
                $modeloUsuario = new Usuario($this->conn);

                //llamamos un metodo para verificar si es el queremos
                $usuarioBD = $modeloUsuario->obtenerPorCorreo($correo);

                // !null == true
                if (!$usuarioBD) {
                    $mensajeError = "Usuario o contraseña incorrectos.";
                } else {

                    if ($password === $usuarioBD['password_hash']) {
                        $_SESSION['id_usuario'] = $usuarioBD['id_usuario'];
                        $_SESSION['nombre']     = $usuarioBD['nombre'];
                        $_SESSION['correo']     = $usuarioBD['correo'];
                        $_SESSION['rol']        = $usuarioBD['rol'];
                        if($_SESSION['rol'] === "admin"){
                            header("Location: menu_admin.php");
                        }else if($_SESSION['rol'] === "proveedor"){
                            header("Location: menu_provee.php");
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
