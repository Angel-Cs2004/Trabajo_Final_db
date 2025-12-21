<?php

require_once __DIR__ . '/../models/Negocio.php';

class NegocioController
{
    private $conn; 

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    private function asegurarSesion()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }
    private function authorize(string $mod, string $perm): void
    {
        if (!can($mod, $perm)) {
            http_response_code(403);
            exit('No autorizado');
        }
    }

    public function index()
    {
        $this->asegurarSesion();

        $modeloNegocio = new Negocio($this->conn);

        // super_admin y admin_negocio ven todos los negocios
            $negocios = $modeloNegocio->obtenerTodos();


        require __DIR__ . '/../views/negocios/index.php';
    }

    public function crear()
    {
        $this->asegurarSesion();

        // Si es admin_negocio o super_admin → puede asignar propietario
            $modeloUsuario = new Usuarios($this->conn);
            $usuarios = $modeloUsuario->obtenerTodos();


        require __DIR__ . '/../views/negocios/crear.php';
    }
    public function crearPropietario()
    {
        $this->asegurarSesion();

        // Aquí NO cargamos lista de usuarios, porque el propietario será el de la sesión
        require __DIR__ . '/../views/negocios/crear_propietario.php';
    }


    public function guardar()
        {
            $this->asegurarSesion();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: index.php?c=negocio&a=listar");
                exit;
            }

            $nombre       = trim($_POST['nombre'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $estado       = $_POST['estado'] ?? 'activo';
            $horaApertura = $_POST['hora_apertura'] ?? '';
            $horaCierre   = $_POST['hora_cierre'] ?? '';
            $imagen_logo  = null;

            // id_propietario

            $idPropietario = (int)$_SESSION['id_usuario'];


            if ($nombre === '' || $horaApertura === '' || $horaCierre === '' || $idPropietario <= 0) {
                header("Location: index.php?c=negocio&a=crearPropietario");
                exit;
            }

            $modeloNegocio = new Negocio($this->conn);

            $modeloNegocio->crear(
                $nombre,
                $descripcion,
                $imagen_logo,
                $estado,
                $horaApertura,
                $horaCierre,
                $idPropietario
            );

            // DESPUÉS DE GUARDAR → MIS NEGOCIOS
            header("Location: index.php?c=negocio&a=listar");
            exit;
    }
    public function guardarPorId()
        {
            $this->asegurarSesion();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: index.php?c=negocio&a=misNegocios");
                exit;
            }

            $nombre       = trim($_POST['nombre'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $estado       = $_POST['estado'] ?? 'activo';
            $horaApertura = $_POST['hora_apertura'] ?? '';
            $horaCierre   = $_POST['hora_cierre'] ?? '';
            $imagen_logo  = null;

            // id_propietario
            $idPropietario = (int)$_SESSION['id_usuario'];
            

            if ($nombre === '' || $horaApertura === '' || $horaCierre === '' || $idPropietario <= 0) {
                header("Location: index.php?c=negocio&a=crearPropietario");
                exit;
            }

            $modeloNegocio = new Negocio($this->conn);

            $modeloNegocio->crear(
                $nombre,
                $descripcion,
                $imagen_logo,
                $estado,
                $horaApertura,
                $horaCierre,
                $idPropietario
            );

            // DESPUÉS DE GUARDAR → MIS NEGOCIOS
            header("Location: index.php?c=negocio&a=misNegocios");
            exit;
    }
    

    public function editar()
    {
        $this->asegurarSesion();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        $modeloNegocio = new Negocio($this->conn);
        $negocio = $modeloNegocio->obtenerPorId($id);

        if (!$negocio) {
            header("Location: index.php?c=negocio&a=listar");
            exit;
        }

        // Propietario solo puede editar lo suyo
        if ($_SESSION['rol'] !== 'admin_negocio' && $_SESSION['rol'] !== 'super_admin') {
            if ((int)$negocio['id_propietario'] !== (int)$_SESSION['id_usuario']) {
                header("Location: index.php?c=negocio&a=listar");
                exit;
            }
        }

        if ($_SESSION['rol'] === 'admin_negocio' || $_SESSION['rol'] === 'super_admin') {
            $modeloUsuario = new Usuarios($this->conn);
            $usuarios = $modeloUsuario->obtenerTodos();
        } else {
            $usuarios = [];
        }

        require __DIR__ . '/../views/negocios/editar.php';
    }

    public function actualizar()
{
    $this->asegurarSesion();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?c=negocio&a=listar");
        exit;
    }

    $id           = intval($_POST['id_negocio'] ?? 0);
    $nombre       = trim($_POST['nombre'] ?? '');
    $descripcion  = trim($_POST['descripcion'] ?? '');
    $estado       = $_POST['estado'] ?? 'activo';
    $horaApertura = $_POST['hora_apertura'] ?? '';
    $horaCierre   = $_POST['hora_cierre'] ?? '';

    // NUEVO: leer propietario y logo del formulario
    $idPropPost   = isset($_POST['id_propietario']) ? (int)$_POST['id_propietario'] : null;
    $imagenPost   = trim($_POST['imagen_logo'] ?? '');

    $modeloNegocio = new Negocio($this->conn);
    $negocioActual = $modeloNegocio->obtenerPorId($id);

    if (!$negocioActual) {
        header("Location: index.php?c=negocio&a=listar");
        exit;
    }

    // Si no se envió propietario o viene vacío, mantenemos el actual
    if ($idPropPost && $idPropPost > 0) {
        $idPropietario = $idPropPost;
    } else {
        $idPropietario = (int)$negocioActual['id_propietario'];
    }

    // Si no se envía logo o se deja vacío, se mantiene el actual
    if ($imagenPost !== '') {
        $imagen_logo = $imagenPost;
    } else {
        $imagen_logo = $negocioActual['imagen_logo'];
    }

    // Validación mínima
    if (
        $id <= 0 ||
        $nombre === '' ||
        $horaApertura === '' ||
        $horaCierre === ''
    ) {
        header("Location: index.php?c=negocio&a=editar&id_negocio=".$id);
        exit;
    }

    $modeloNegocio->actualizar(
        $id,
        $nombre,
        $descripcion,
        $imagen_logo,
        $estado,
        $horaApertura,
        $horaCierre,
        $idPropietario   // OJO: ahora sí pasa el propietario que elegiste
    );

    header("Location: index.php?c=negocio&a=listar");
    exit;
}


    public function perfil()
    {
        $this->asegurarSesion();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=negocio&a=misNegocios");
            exit;
        }

        $modeloNegocio = new Negocio($this->conn);
        $negocio = $modeloNegocio->obtenerPorId($id);

        if (!$negocio) {
            header("Location: index.php?c=negocio&a=misNegocios");
            exit;
        }

        // seguridad: solo el dueño puede editarlo
        if ((int)$negocio['id_propietario'] !== (int)$_SESSION['id_usuario']) {
            header("Location: index.php?c=negocio&a=misNegocios");
            exit;
        }
 
        require __DIR__ . '/../views/negocios/perfil.php';
    }


    public function listar()
    {
        // Puedes simplemente reutilizar index()
        $propietariosModel = new Usuarios($this->conn);
        $propietarios =  $propietariosModel->obtenerTodos();
        $this->index();
    }

    public function misNegocios()
    {
        $this->asegurarSesion();

        $modeloNegocio = new Negocio($this->conn);
        $negocios = $modeloNegocio->obtenerPorPropietario((int)$_SESSION['id_usuario']);

        require __DIR__ . '/../views/negocios/mis_negocios.php';
    }
    public function actualizarPropietario()
    {
        $this->asegurarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=negocio&a=misNegocios");
            exit;
        }

        $id           = intval($_POST['id_negocio'] ?? 0);
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $estado       = $_POST['estado'] ?? 'activo';
        $horaApertura = $_POST['hora_apertura'] ?? '';
        $horaCierre   = $_POST['hora_cierre'] ?? '';

        if ($id <= 0 || $nombre === '' || $horaApertura === '' || $horaCierre === '') {
            header("Location: index.php?c=negocio&a=perfil&id={$id}");
            exit;
        }

        $modeloNegocio = new Negocio($this->conn);
        $negocioActual = $modeloNegocio->obtenerPorId($id);

        // validar que existe y que el negocio es del usuario
        if (
            !$negocioActual ||
            (int)$negocioActual['id_propietario'] !== (int)$_SESSION['id_usuario']
        ) {
            header("Location: index.php?c=negocio&a=misNegocios");
            exit;
        }

        $imagen_logo = $negocioActual['imagen_logo']; // lo reutilizamos

        // null para NO cambiar el propietario
        $modeloNegocio->actualizar(
            $id,
            $nombre,
            $descripcion,
            $imagen_logo,
            $estado,
            $horaApertura,
            $horaCierre,
            null
        );

        // DESPUÉS DE EDITAR → MIS NEGOCIOS
        header("Location: index.php?c=negocio&a=misNegocios");
        exit;
    }



}
