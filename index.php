<?php
// Iniciar la sesión PHP. Esto es esencial para mantener al usuario autenticado.
session_start();

// Si el usuario ya está logueado, redirigir a la página de dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

// Incluir el archivo de configuración de la base de datos
require_once 'config.php';

// Definir variables e inicializarlas con valores vacíos
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Procesar datos del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Validar nombre de usuario
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, introduce tu nombre de usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    // 2. Validar contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, introduce tu contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // 3. Validar credenciales
    if (empty($username_err) && empty($password_err)) {
        // Preparar una sentencia SELECT
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Vincular parámetros a la sentencia preparada
            $stmt->bind_param("s", $param_username);

            // Establecer parámetros
            $param_username = $username;

            // Intentar ejecutar la sentencia preparada
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();

                // Verificar si el nombre de usuario existe, si es así, verificar la contraseña
                if ($stmt->num_rows == 1) {
                    // Vincular variables de resultado
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        // Verificar la contraseña usando password_verify()
                        if (password_verify($password, $hashed_password)) {
                            // Contraseña correcta, iniciar una nueva sesión
                            session_regenerate_id(true); // Generar un nuevo ID de sesión para mayor seguridad
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirigir al usuario a la página de dashboard
                            header("location: dashboard.php");
                        } else {
                            // Contraseña incorrecta
                            $login_err = "Nombre de usuario o contraseña inválidos.";
                        }
                    }
                } else {
                    // Nombre de usuario no existe
                    $login_err = "Nombre de usuario o contraseña inválidos.";
                }
            } else {
                echo "¡Ups! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar la sentencia
            $stmt->close();
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <p>Por favor, introduce tus credenciales para iniciar sesión.</p>

        <?php
        // Mostrar mensaje de error de login si existe
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>
            <p>¿No tienes una cuenta? No hay opción de registro en este ejemplo, pero en un sistema real iría aquí.</p>
        </form>
    </div>
</body>
</html>