<?php
// Datos de conexión a la base de datos
$host = "localhost"; // Cambia esto al nombre de tu servidor de base de datos PostgreSQL
$port = "5432"; // Puerto predeterminado de PostgreSQL
$dbname = "postgres"; // Nombre de tu base de datos
$user = "postgres"; // Nombre de usuario de PostgreSQL
$password = "3225"; // Contraseña de PostgreSQL

try {
    // Intenta establecer la conexión
    $con = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    
    // Configura PDO para mostrar errores de SQL
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Establece el juego de caracteres a UTF-8
    $con->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // En caso de error, muestra un mensaje de error y termina el script
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
