<?php

class CategoriasController
{
    public function listar()
    {
        require __DIR__ . '/../views/categorias/index.php';
    }

    public function crear()
    {
        require __DIR__ . '/../views/categorias/crear.php';
    }

    public function editar()
    {
        require __DIR__ . '/../views/categorias/editar.php';
    }
}
