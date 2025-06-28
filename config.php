<?php
// Credenciales de la base de datos
define('DB_SERVER', 'localhost'); // La dirección del servidor de la base de datos (normalmente localhost en XAMPP)
define('DB_USERNAME', 'root');    // El nombre de usuario de la base de datos (root por defecto en XAMPP)
define('DB_PASSWORD', '');        // La contraseña de la base de datos (vacía por defecto en XAMPP)
define('DB_NAME', 'login_db');    // El nombre de la base de datos que creaste

// Intentar conectar a la base de datos MySQL
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if ($conn->connect_error) {
    die("ERROR: No se pudo conectar a la base de datos. " . $conn->connect_error);
}
?>