<?php

require_once __DIR__ . '/../models/Categoria.php';

class CategoriasController
{
    private mysqli $conn;
    private Categoria $categoriaModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->categoriaModel = new Categoria($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function listar(): void
    {
        $categorias = $this->categoriaModel->obtenerTodas();
        require __DIR__ . '/../views/categoria/index.php';
    }

    public function crear(): void
    {
        require __DIR__ . '/../views/categoria/crear.php';
    }

    public function guardar(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;

        if ($nombre !== '') {
            $this->categoriaModel->crear($nombre, $descripcion, $activo);
        }

        header('Location: index.php?c=categorias&a=listar');
        exit;
    }

    // (OPCIONAL) editar / actualizar:
    public function editar(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: index.php?c=categorias&a=listar');
            exit;
        }

        $categoria = $this->categoriaModel->obtenerPorId($id);
        if (!$categoria) {
            header('Location: index.php?c=categorias&a=listar');
            exit;
        }

        require __DIR__ . '/../views/categoria/editar.php';
    }

    public function actualizar(): void
    {
        $id = isset($_POST['id_categoria']) ? (int) $_POST['id_categoria'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;

        if ($id > 0 && $nombre !== '') {
            $this->categoriaModel->actualizar($id, $nombre, $descripcion, $activo);
        }

        header('Location: index.php?c=categorias&a=listar');
        exit;
    }
}
