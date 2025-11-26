<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard - Yahuarcocha'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Navbar o sidebar  -->
        <?php include __DIR__ . '/navbar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                </div>
                <!-- Usuario -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Admin</span>
                    <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                        A
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 p-6 overflow-auto">
