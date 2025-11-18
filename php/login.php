<?php
/*
  ======================================================================
  Página: login.php (Dentro de la carpeta /php/)
  ¡USAMOS RUTAS ABSOLUTAS PARA LA REDIRECCIÓN!
  ======================================================================
*/

// --- 1. DEFINICIÓN DE BASE_URL Y SESIÓN (ANTES DE LA REDIRECCIÓN) ---
// La constante BASE_URL ya está definida en header.php, pero la definimos
// aquí por si el script se sale antes de cargar el header.
define('BASE_URL', '/html/ProyectoFinal'); 
session_start(); // Aseguramos que la sesión inicie antes de usar $_SESSION

// --- 2. LÓGICA DE PHP (Procesar el formulario) ---
$error = ''; 

// Revisa si el usuario ENVIÓ el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // La conexión a la BD debe ser la primera línea dentro del bloque POST
    require_once '../includes/db_connect.php';

    $correo = $_POST['correo_electronico'];
    $contrasena_plana = $_POST['contrasena'];

    // 1. Preparar la consulta SQL para BUSCAR al usuario por su correo
    $sql = "SELECT * FROM Usuarios WHERE correo_electronico = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // 2. Verificar si el usuario existe
    if ($usuario = mysqli_fetch_assoc($resultado)) {
        
        // 3. Verificar si la contraseña coincide con el HASH
        if (password_verify($contrasena_plana, $usuario['contrasena'])) {
            
            // ¡ÉXITO! La contraseña es correcta.
            
            // 4. Guardar los datos del usuario en la SESIÓN
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            // (Si tuvieras un campo de admin, lo pones aquí)
            // $_SESSION['es_admin'] = $usuario['es_admin']; 

            // 5. ¡REDIRECCIÓN CORREGIDA! 
            // Usamos la ruta absoluta: /html/ProyectoFinal/index.php
            header("Location: " . BASE_URL . "/index.php");
            exit; // ¡Importante! Detener el script después de redirigir

        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    } else {
        $error = "Correo o contraseña incorrectos.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}


// --- 3. CÓDIGO HTML (Mostrar la página y el formulario) ---
require_once '../includes/header.php'; 
?>

  <!-- Contenido único de la página de Login -->
  <div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center mb-4">Iniciar Sesión</h1>
            <p class="text-center">Bienvenido de vuelta. Ingresa a tu cuenta.</p>

            <!-- Mostramos el mensaje de error (si existe) -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de Login (Action corregida en la respuesta anterior) -->
            <form action="php/login.php" method="POST" class="border p-4 rounded shadow-sm" style="background-color: #fff;">
                
                <div class="mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                </div>
                
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Entrar</button>

            </form>

            <div class="text-center mt-3">
                <p>¿No tienes una cuenta? <a href="registro.php">Crea una aquí</a>.</p>
            </div>

        </div>
    </div>
  </div>

<?php 
require_once '../includes/footer.php'; 
?>