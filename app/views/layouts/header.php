<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$rolUsuario    = $_SESSION['rol']    ?? null;
$nombre = $_SESSION['nombre'] ?? 'Usuario';
$pageTitle = $pageTitle ?? 'Dashboard - Yahuarcocha';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" href="/Trabajo_Final_db/public/img/logo.ico?v=2" type="image/x-icon">
    <link rel="icon" href="/Trabajo_Final_db/public/img/logo.png?v=2" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gradient-to-br from-slate-100 via-gray-100 to-slate-200">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php
                include __DIR__ . '/navbar.php';

        ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        <?= htmlspecialchars($nombre) ?>
                    </span>
                    <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                        <?= strtoupper(substr($nombre, 0, 1)) ?>
                    </div>
                </div>
            </header>


