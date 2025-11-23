<?php
$mensajeError = "";

// Comprobar si el formulario se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Leer los datos que llegaron del formulario
    $usuario  = $_POST['usuario']  ?? '';
    $password = $_POST['password'] ?? '';

    // 2. Limpiar espacios
    $usuario  = trim($usuario);
    $password = trim($password);

    // 3. Validar que no estén vacíos
    if ($usuario === '' || $password === '') {
        $mensajeError = "Debes completar usuario y contraseña.";
    } else {
        // 4. Credenciales correctas (hardcode por ahora)
        $usuario_correcto  = "daniel";
        $password_correcta = "1234";

        // 5. Comparar
        if ($usuario === $usuario_correcto && $password === $password_correcta) {
            // 6. Si es correcto, redirigir a otra página
            header("Location: bienvenido.php");
            exit;
        } else {
            // 7. Si es incorrecto, guardar mensaje de error
            $mensajeError = "Usuario o contraseña incorrectos.";
        }
    }
}

// A partir de aquí ya podemos mostrar HTML
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login básico</title>
</head>
<body>
    <h1>Iniciar sesión</h1>

    <form action="index.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario">

        <br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password">

        <br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
