<?php
// 1. Inicia el motor de sesiones
session_start();

// 2. Destruye todas las variables de sesión
session_unset();

// 3. Destruye la sesión
session_destroy();

/*
  4. Redirige al usuario de vuelta al inicio (index.php).
     Usamos la constante BASE_URL que definimos en el header.php
     (Aunque no lo incluimos, podemos definirla aquí para seguridad)
*/
$base_url = '/html/ProyectoFinal'; // (Asegúrate que esta sea tu ruta base)
header('Location: ' . $base_url . '/index.php');
exit; // Termina el script
?>