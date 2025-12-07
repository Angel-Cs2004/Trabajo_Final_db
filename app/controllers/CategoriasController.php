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

        // Aseguramos sesión para mensajes flash / auth
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si quieres exigir login para todo el módulo, descomenta esto:
        /*
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        */
    }

    /**
     * Listar todas las categorías
     * Ruta: index.php?c=categorias&a=listar
     */
    public function listar(): void
    {
        $categorias = $this->categoriaModel->obtenerTodas();
        // Vista: app/views/categorias/index.php
        require __DIR__ . '/../views/categorias/index.php';
    }

    /**
     * Mostrar formulario para crear una categoría
     * Ruta: index.php?c=categorias&a=crear
     */
    public function crear(): void
    {
        // Datos vacíos para reutilizar el mismo form en crear/editar
        $categoria = [
            'id_categoria' => null,
            'nombre'       => '',
            'descripcion'  => '',
            'estado'       => 'activo',
        ];

        // Vista: app/views/categorias/crear.php (o form.php, como decidas)
        require __DIR__ . '/../views/categorias/crear.php';
    }

    /**
     * Guardar nueva categoría (POST)
     * Ruta: index.php?c=categorias&a=guardar
     */
    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=categorias&a=listar');
            exit;
        }

        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';

        if ($nombre === '') {
            $_SESSION['error'] = 'El nombre de la categoría es obligatorio.';
            header('Location: index.php?c=categorias&a=crear');
            exit;
        }

        // Método que deberás tener en app/models/Categoria.php
        // por ejemplo:
        // public function crear(string $nombre, ?string $descripcion, string $estado): bool
        $this->categoriaModel->crear($nombre, $descripcion, $estado);

        $_SESSION['exito'] = 'Categoría creada correctamente.';
        header('Location: index.php?c=categorias&a=listar');
        exit;
    }
}
