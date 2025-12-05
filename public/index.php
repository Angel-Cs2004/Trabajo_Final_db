<?php

// Configuración de base de datos
require_once __DIR__ . '/../app/config/db.php';

// Controladores
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/ProductoNegocioController.php';
require_once __DIR__ . '/../app/controllers/ProductoGeneralController.php';
require_once __DIR__ . '/../app/controllers/RolesController.php';
require_once __DIR__ . '/../app/controllers/ParametroController.php';
require_once __DIR__ . '/../app/controllers/UsuariosController.php';
require_once __DIR__ . '/../app/controllers/NegocioController.php';
require_once __DIR__ . '/../app/controllers/ReportesController.php';
require_once __DIR__ . '/../app/controllers/CategoriasController.php';

// Router MVC básico
$c = $_GET['c'] ?? 'auth';   
$a = $_GET['a'] ?? 'login';  

switch ($c) {

    // AUTH
    case 'auth':
        $controller = new AuthController($conn);

        if ($a === 'login') {
            $controller->login();
        } elseif ($a === 'logout') {
            session_start();
            session_destroy();
            header('Location: index.php?c=auth&a=login');
            exit;
        } else {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        break;

    // HOME (Dashboards)
    case 'home':
        $controller = new HomeController($conn);

        if ($a === 'dashboardAdmin') {
            $controller->dashboardAdmin();
        } elseif ($a === 'dashboardProveedor') {
            $controller->dashboardProveedor();
        } else {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        break;

    // USUARIOS (CRUD)
    case 'usuarios':
        $controller = new UsuariosController($conn);

        if ($a === 'index') {
            $controller->index();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } elseif ($a === 'editar') {
            $controller->editar();
        } elseif ($a === 'actualizar') {
            $controller->actualizar();
        } else {
            header('Location: index.php?c=usuarios&a=index');
            exit;
        }
        break;

    // ROLES
    case 'roles':
        $controller = new RolesController($conn);

        if ($a === 'index') {
            $controller->index();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } elseif ($a === 'editar') {
            $controller->editar();
        } elseif ($a === 'actualizar') {
            $controller->actualizar();
        } else {
            header('Location: index.php?c=roles&a=index');
            exit;
        }
        break;

    // PARÁMETROS
    case 'parametros':
        $controller = new ParametrosController($conn);

        if ($a === 'index') {
            $controller->index();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } elseif ($a === 'editar') {
            $controller->editar();
        } elseif ($a === 'actualizar') {
            $controller->actualizar();
        } else {
            header('Location: index.php?c=parametros&a=index');
            exit;
        }
        break;

    // NEGOCIOS
    case 'negocio':
        $controller = new NegocioController($conn);

        if ($a === 'listar') {
            $controller->index();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } elseif ($a === 'editar') {
            $controller->editar();
        } elseif ($a === 'actualizar') {
            $controller->actualizar();
        } elseif ($a === 'perfil') {
            $controller->perfil();    
        } else {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }
        break;

    // PRODUCTOS
    case 'productoNegocio':
        $controller = new ProductoNegocioController($conn);

        if ($a === 'listar') {
            $controller->listar();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } else {
            header('Location: index.php?c=productoTienda&a=listar');
            exit;
        }
        break;

    case 'productoGeneral':
        $controller = new ProductoGeneralController($conn);

        if ($a === 'listar') {
            $controller->listar();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } else {
            header('Location: index.php?c=productoGeneral&a=listar');
            exit;
        }
        break;

    case 'categorias':
        $controller = new CategoriasController($conn);

        if ($a === 'listar') {
            $controller->listar();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } else {
            header('Location: index.php?c=productoTienda&a=listar');
            exit;
        }
        break;

    // REPORTES
    case 'negocio':
        $controller = new NegocioController($conn);

        if ($a === 'listar') {
            $controller->listar();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } elseif ($a === 'editar') {
            $controller->editar();
        } elseif ($a === 'actualizar') {
            $controller->actualizar();
        } else {
            header('Location: index.php?c=negocio&a=listar');
            exit;
        }
        break;


    case 'reporte':
        $controller = new ReportesController($conn);

        if ($a === 'reporteGeneral') {
            $controller->ReporteGeneral();
        } elseif ($a === 'reporteNegocio') {
            $controller->ReporteNegocio();
        } else {
            header('Location: index.php?c=home&a=dashboardAdmin');
            exit;
        }
        break;

    default:
        header('Location: index.php?c=auth&a=login');
        exit;
}
