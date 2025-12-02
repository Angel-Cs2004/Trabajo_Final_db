<?php

require_once __DIR__ . '/../models/Parametro.php';

class ParametrosController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Verifica sesión y rol de administrador
    private function asegurarSesionAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'super_admin') {
            header("Location: index.php?c=home&a=dashboardProveedor");
            exit;
        }
    }

    // Listar parámetros
    public function index()
    {
        $this->asegurarSesionAdmin();

        $modelo = new ParametroImagen($this->conn);
        $parametros = $modelo->obtenerTodos();

        require __DIR__ . '/../views/parametros/index.php';
    }

    // Formulario de creación
    public function crear()
    {
        $this->asegurarSesionAdmin();
        require __DIR__ . '/../views/parametros/crear.php';
    }

    // Guardar nuevo parámetro
    public function guardar()
    {
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=parametros&a=index");
            exit;
        }

        $etiqueta   = trim($_POST['etiqueta'] ?? '');
        $tipo       = trim($_POST['tipo'] ?? '');
        $ancho      = intval($_POST['ancho_px'] ?? 0);
        $alto       = intval($_POST['alto_px'] ?? 0);
        $tamano     = intval($_POST['tamano_kb'] ?? 0);
        $categoria  = trim($_POST['categoria_admin'] ?? '');
        $formatos   = trim($_POST['formatos_validos'] ?? '');
        $activo     = ($_POST['activo'] ?? '0') == '1' ? 1 : 0;

        if ($etiqueta === '' || $tipo === '' || $ancho <= 0 || $alto <= 0 || $tamano <= 0 || $categoria === '') {
            header("Location: index.php?c=parametros&a=crear");
            exit;
        }

        $modelo = new ParametroImagen($this->conn);
        $modelo->crear($etiqueta, $tipo, $ancho, $alto, $tamano, $categoria, $formatos, $activo);

        header("Location: index.php?c=parametros&a=index");
        exit;
    }

    // Formulario de edicion
    public function editar()
    {
        $this->asegurarSesionAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?c=parametros&a=index");
            exit;
        }

        $modelo = new ParametroImagen($this->conn);
        $param = $modelo->obtenerPorId($id);

        require __DIR__ . '/../views/parametros/editar.php';
    }

    // Actualizar parametro
    public function actualizar()
    {
        $this->asegurarSesionAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=parametros&a=index");
            exit;
        }

        $id         = intval($_POST['id_parametro_imagen'] ?? 0);
        $etiqueta   = trim($_POST['etiqueta']);
        $tipo       = trim($_POST['tipo']);
        $ancho      = intval($_POST['ancho_px']);
        $alto       = intval($_POST['alto_px']);
        $tamano     = intval($_POST['tamano_kb']);
        $categoria  = trim($_POST['categoria_admin']);
        $formatos   = trim($_POST['formatos_validos']);
        $activo     = ($_POST['activo'] ?? '0') == '1' ? 1 : 0;

        if ($id <= 0) {
            header("Location: index.php?c=parametros&a=index");
            exit;
        }

        $modelo = new ParametroImagen($this->conn);
        $modelo->actualizar($id, $etiqueta, $tipo, $ancho, $alto, $tamano, $categoria, $formatos, $activo);

        header("Location: index.php?c=parametros&a=index");
        exit;
    }
}
