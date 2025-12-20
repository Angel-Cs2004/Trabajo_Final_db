<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once __DIR__ . '/../models/ProductoNegocio.php';
require_once __DIR__ . '/../models/Negocio.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoNegocioController
{
    private mysqli $conn;
    private function urlImagenValida(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        // Validar formato
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Intentar hacer HEAD o GET rápido
        $headers = @get_headers($url, 1);
        if ($headers === false) {
            return false;
        }

        // Código de respuesta
        $lineaStatus = $headers[0] ?? '';
        if (stripos($lineaStatus, '200') === false) {
            return false;
        }

        // Tipo de contenido
        $contentType = '';
        if (is_array($headers['Content-Type'] ?? null)) {
            $contentType = $headers['Content-Type'][0];
        } else {
            $contentType = $headers['Content-Type'] ?? '';
        }

        return stripos($contentType, 'image/') !== false;
    }

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    public function listar()
    {
        $negociosMode1 = new Negocio($this->conn);
        $negocios = $negociosMode1->obtenerPorPropietario((int)($_SESSION['id_usuario'] ?? 0));
        $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
        
        if ($idUsuario <= 0) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $productoNegocioModel = new ProductoNegocio($this->conn);

        // obtenerTodos ahora interpreta el parámetro como id_usuario propietario
        $productos = $productoNegocioModel->obtenerTodos($idUsuario);

        require __DIR__ . '/../views/productos/Negocio/index.php';
    }

    public function crear()
    {
        $categoriaModel = new Categoria($this->conn);
        $categorias = $categoriaModel->obtenerTodasActivas();
        $negociosMode1 = new Negocio($this->conn);
        $negocios = $negociosMode1->obtenerPorPropietario((int)($_SESSION['id_usuario'] ?? 0));

         if (!$negocios || count($negocios) === 0) {
            // si no tiene negocios, lo mandamos a crear uno
            header("Location: index.php?c=negocio&a=crear");
            exit;
        }

        require __DIR__ . '/../views/productos/Negocio/crear.php';
    }

    // Procesa el POST del formulario
    public function guardar()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?c=productoNegocio&a=crear");
        exit;
    }

    $productoNegocioModel = new ProductoNegocio($this->conn);
    $negocioModel         = new Negocio($this->conn);

    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    if ($idUsuario <= 0) {
        header("Location: index.php?c=auth&a=login");
        exit;
    }

    // Negocios del usuario
    $negocios = $negocioModel->obtenerPorPropietario($idUsuario);
    if (!$negocios || count($negocios) === 0) {
        header("Location: index.php?c=negocio&a=crear");
        exit;
    }

    $idNegocio = (int)($_POST['id_negocio'] ?? 0);
    if ($idNegocio <= 0) {
        header("Location: index.php?c=productoNegocio&a=crear");
        exit;
    }

    $pertenece = false;
    foreach ($negocios as $neg) {
        if ((int)$neg['id_negocio'] === $idNegocio) {
            $pertenece = true;
            break;
        }
    }

    if (!$pertenece) {
        // intento de usar un negocio que no es suyo
        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    // Datos del producto
    $nombre      = trim($_POST['nombre'] ?? '');
    $precio      = (float)($_POST['precio'] ?? 0);
    $urlImagen   = trim($_POST['url_imagen'] ?? '');
    $estado      = $_POST['estado'] ?? 'activo';
    $idCategoria = (int)($_POST['id_categoria'] ?? 0);

    // Validación mínima
    if ($nombre === '' || $precio <= 0 || $idCategoria <= 0) {
        header("Location: index.php?c=productoNegocio&a=crear");
        exit;
    }

    // Crear SOLO en la tienda seleccionada
    $res = $productoNegocioModel->crearProducto(
        $idNegocio,
        $nombre,
        $precio,
        $urlImagen,
        $estado,
        $idCategoria
    );

    $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
    $_SESSION['flash_msg']  = $res['msg'];

    header("Location: index.php?c=productoNegocio&a=listar");
    exit;

}


    public function editar()
    {
        $id_producto = $_GET['id_producto'] ?? null;
        if (!$id_producto) {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        $productoNegocio = new ProductoNegocio($this->conn);
        $categoriaModel  = new Categoria($this->conn);

        $producto   = $productoNegocio->obtenerPorId((int)$id_producto);
        $categorias = $categoriaModel->obtenerTodasActivas();

        if (!$producto) {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        require __DIR__ . '/../views/productos/Negocio/editar.php';
    }

    // Procesa el POST del formulario de edición
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        $productoNegocio = new ProductoNegocio($this->conn);

        $id_producto = (int)($_POST['id_producto'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $urlImagen   = trim($_POST['url_imagen'] ?? '');
        $estado      = $_POST['estado'] ?? 'activo';
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);

        if ($id_producto <= 0 || $nombre === '' || $precio <= 0 || $idCategoria <= 0) {
            header("Location: index.php?c=productoNegocio&a=listar");
            exit;
        }

        $res = $productoNegocio->editarProducto(
            $id_producto,
            $nombre,
            $precio,
            $urlImagen,
            $estado,
            $idCategoria
        );

        $_SESSION['flash_tipo'] = $res['ok'] ? 'success' : 'error';
        $_SESSION['flash_msg']  = $res['msg'];

        header("Location: index.php?c=productoNegocio&a=listar");
        exit;

    }

    public function eliminar()
    {
        $id_producto = $_GET['id_producto'] ?? null;
        if ($id_producto) {
            $productoNegocio = new ProductoNegocio($this->conn);
            $productoNegocio->eliminarProducto((int)$id_producto);
        }

        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }
   public function importar()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    if (empty($_FILES['archivo_productos']) || $_FILES['archivo_productos']['error'] !== UPLOAD_ERR_OK) {
        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    $tmpPath = $_FILES['archivo_productos']['tmp_name'];
    $nombreArchivo = $_FILES['archivo_productos']['name'] ?? '';

    // Extensión
    $ext = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    if (!in_array($ext, ['xlsx', 'xls'])) {
        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    // Usuario logueado
    $idUsuario = (int)($_SESSION['id_usuario'] ?? 0);
    if ($idUsuario <= 0) {
        header("Location: index.php?c=auth&a=login");
        exit;
    }

    $productoModel  = new ProductoNegocio($this->conn);
    $negocioModel   = new Negocio($this->conn);
    $categoriaModel = new Categoria($this->conn);

    // Negocios del usuario
    $negociosUsuario = $negocioModel->obtenerPorPropietario($idUsuario);
    if (!$negociosUsuario || count($negociosUsuario) === 0) {
        header("Location: index.php?c=negocio&a=crear");
        exit;
    }

    $idNegocio = (int)($_POST['id_negocio'] ?? 0);

    if ($idNegocio <= 0) {
        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    $pertenece = false;
    foreach ($negociosUsuario as $neg) {
        if ((int)$neg['id_negocio'] === $idNegocio) {
            $pertenece = true;
            break;
        }
    }

    if (!$pertenece) {
        // intento de hack, no permitir
        header("Location: index.php?c=productoNegocio&a=listar");
        exit;
    }

    // Cargar Excel
    $spreadsheet = IOFactory::load($tmpPath);
    $sheet = $spreadsheet->getActiveSheet();

    $creados = 0;
    $conErrorImagen = 0;
    $filasSaltadas = 0;

    // A: nombre, B: categoria, C: precio, D: url_imagen
    $filaInicial = 2;
    $ultimaFila = $sheet->getHighestRow();

    for ($row = $filaInicial; $row <= $ultimaFila; $row++) {

        $nombre    = trim((string)$sheet->getCell("A{$row}")->getValue());
        $nomCat    = trim((string)$sheet->getCell("B{$row}")->getValue());
        $precioVal = $sheet->getCell("C{$row}")->getValue();
        $precio    = (float)$precioVal;
        $urlImagen = trim((string)$sheet->getCell("D{$row}")->getValue());

        // Validación mínima
        if ($nombre === '' || $nomCat === '' || $precio <= 0) {
            $filasSaltadas++;
            continue;
        }

        // Categoría: buscar o crear
        $categoria = $categoriaModel->buscarPorNombre($nomCat);
        if ($categoria) {
            $idCategoria = (int)$categoria['id_categoria'];
        } else {
            $nuevoIdCat = $categoriaModel->crearRapida($nomCat);
            if ($nuevoIdCat === null) {
                $filasSaltadas++;
                continue;
            }
            $idCategoria = $nuevoIdCat;
        }

        // Validar URL imagen
        if (!$this->urlImagenValida($urlImagen)) {
            $urlImagen = null;
            $conErrorImagen++;
        }

        // Crear producto
        $res = $productoModel->crearProducto(
            $idNegocio,
            $nombre,
            $precio,
            $urlImagen,
            'activo',
            $idCategoria
        );

        if ($res['ok']) {
            $creados++;
        } else {
            $filasSaltadas++;
        }
    }

    // Guardar mensaje
    $_SESSION['import_msg'] =
        "Productos creados: {$creados}. Filas con error: {$filasSaltadas}. Productos con imagen no válida: {$conErrorImagen}.";

    header("Location: index.php?c=productoNegocio&a=listar");
    exit;
}

}
