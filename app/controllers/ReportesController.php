<?php

class ReportesController
{
    public function ReporteGeneral()
    {
        require __DIR__ . '/../views/Reportes/General/index.php';
    }

    public function ReporteNegocio()
    {
        require __DIR__ . '/../views/Reportes/Negocio/index.php';
    }

}
