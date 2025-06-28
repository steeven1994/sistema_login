<?php
// Iniciar la sesión PHP para acceder a los datos del usuario logueado
session_start();

// Verificar si el usuario no está logueado. Si no lo está, redirigir a la página de login.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
        <p>Has iniciado sesión correctamente. Esta es tu página de dashboard.</p>
        <p>Aquí podrías mostrar contenido exclusivo para usuarios autenticados.</p>
        <p>
            <a href="logout.php" class="btn btn-warning">Cerrar Sesión</a>
        </p>
    </div>
</body>
</html>