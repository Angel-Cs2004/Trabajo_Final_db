<?php

class ProductoNegocioController
{
    public function listar()
    {
        require __DIR__ . '/../views/productos/Negocio/index.php';
    }

    public function crear()
    {
        require __DIR__ . '/../views/productos/Negocio/crear.php';
    }

    public function editar()
    {
        require __DIR__ . '/../views/productos/Negocio/editar.php';
    }
}
