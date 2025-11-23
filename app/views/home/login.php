<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login básico</title>

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    >

    <!-- Tu CSS propio  PONES EL QUE QUIEREAS LA VERDAD ME LLEGA AL PIN....--> 
    <link rel="stylesheet" href="">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Iniciar sesión</h1>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($mensajeError); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="mx-auto" style="max-width: 400px;">
            <div class="mb-3">
                <label for="correo" class="form-label">Usuario</label>
                <input type="text" name="correo" id="correo" class="form-control">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
</body>
</html>
