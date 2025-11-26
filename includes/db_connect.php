<?php
// Archivo: includes/db_connect.php
// ¡CONFIGURACIÓN PARA XAMPP!

$servername = "localhost";     // O '127.0.0.1'
$username = "root";            // Usuario por defecto de XAMPP
$password = "";                 // La contraseña por defecto de XAMPP es VACÍA
$dbname = "tienda_online";     // El nombre de tu base de datos


// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if(mysqli_connect_errno()) {
    die("Error de conexión a la Base de Datos: " . mysqli_connect_error());
}

// Asegurarnos de que hable en UTF-8 (para acentos y ñ)
mysqli_set_charset($conn, "utf8mb4");

?>  