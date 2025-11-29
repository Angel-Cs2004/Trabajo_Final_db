<?php

// Configuración de base de datos
require_once __DIR__ . '/../app/config/db.php';

// Controladores
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/NegocioController.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';
require_once __DIR__ . '/../app/controllers/RolesController.php';
require_once __DIR__ . '/../app/controllers/ReporteController.php';
require_once __DIR__ . '/../app/controllers/ParametroController.php';
require_once __DIR__ . '/../app/controllers/UsuariosController.php';

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

    // PRODUCTOS
    case 'producto':
        $controller = new ProductoController($conn);

        if ($a === 'listar') {
            $controller->listar();
        } elseif ($a === 'crear') {
            $controller->crear();
        } elseif ($a === 'guardar') {
            $controller->guardar();
        } else {
            header('Location: index.php?c=producto&a=listar');
            exit;
        }
        break;

    // REPORTES
    case 'reporte':
        $controller = new ReporteController($conn);

        if ($a === 'productosPorNegocio') {
            $controller->productosPorNegocio();
        } elseif ($a === 'productosPorCategoria') {
            $controller->productosPorCategoria();
        } elseif ($a === 'productosRango') {
            $controller->productosRango();
        } elseif ($a === 'negociosPorPropietario') {
            $controller->negociosPorPropietario();
        } elseif ($a === 'cargasMasivas') {
            $controller->cargasMasivas();
        } else {
            header('Location: index.php?c=home&a=dashboardAdmin');
            exit;
        }
        break;

    default:
        header('Location: index.php?c=auth&a=login');
        exit;
}
