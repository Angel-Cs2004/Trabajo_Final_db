<?php

class ProductoGeneralController
{
    public function listar()
    {
        require __DIR__ . '/../views/productos/General/index.php';
    }

    public function crear()
    {
        require __DIR__ . '/../views/productos/General/crear.php';
    }

    public function editar()
    {
        require __DIR__ . '/../views/productos/General/editar.php';
    }
}
