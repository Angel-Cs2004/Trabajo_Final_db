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

        // nombre es opcional
        $nombre    = trim($_POST['nombre'] ?? '');
        $etiqueta  = trim($_POST['etiqueta'] ?? '');
        $ancho     = intval($_POST['ancho_px'] ?? 0);
        $alto      = intval($_POST['alto_px'] ?? 0);
        // en el form puedes llamarlo 'categoria' o 'categoria_admin'; dejo ambos por compatibilidad
        $categoria = trim($_POST['categoria'] ?? ($_POST['categoria_admin'] ?? ''));
        $formatos  = trim($_POST['formatos_validos'] ?? '');


        // Validación mínima
        if ($etiqueta === '' || $ancho <= 0 || $alto <= 0 || $categoria === '' || $formatos === '') {
            header("Location: index.php?c=parametros&a=crear");
            exit;
        }

        $modelo = new ParametroImagen($this->conn);
        $modelo->crear($nombre, $etiqueta, $ancho, $alto, $categoria, $formatos);

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

        if (!$param) {
            header("Location: index.php?c=parametros&a=index");
            exit;
        }

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

        $id        = intval($_POST['id_parametro_imagen'] ?? 0);
        $nombre    = trim($_POST['nombre'] ?? '');
        $etiqueta  = trim($_POST['etiqueta'] ?? '');
        $ancho     = intval($_POST['ancho_px'] ?? 0);
        $alto      = intval($_POST['alto_px'] ?? 0);
        $categoria = trim($_POST['categoria'] ?? ($_POST['categoria_admin'] ?? ''));
        $formatos  = trim($_POST['formatos_validos'] ?? '');


        if ($id <= 0) {
            header("Location: index.php?c=parametros&a=index");
            exit;
        }

        $modelo = new ParametroImagen($this->conn);
        $modelo->actualizar($id, $nombre, $etiqueta, $ancho, $alto, $categoria, $formatos);

        header("Location: index.php?c=parametros&a=index");
        exit;
    }
}
