<?php
/*
  ======================================================================
  Página: registro.php (Dentro de la carpeta /php/)
  ======================================================================
*/

// --- 1. LÓGICA DE PHP (Procesar el formulario) ---
$mensaje = ''; // Para mostrar mensajes de éxito o error

// Primero, nos conectamos a la base de datos
// Usamos la ruta '../' para "subir un nivel" y encontrar la carpeta 'includes'
require_once '../includes/db_connect.php';

// Revisa si el usuario ENVIÓ el formulario (si hizo POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolectar datos del formulario
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['correo_electronico'];
    $contrasena_plana = $_POST['contrasena']; // Contraseña en texto plano
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tarjeta = $_POST['tarjeta_bancaria'];
    $direccion = $_POST['direccion_postal'];

    // 2. ¡SEGURIDAD! Hashear la contraseña.
    // NUNCA guardes contraseñas en texto plano.
    $contrasena_hash = password_hash($contrasena_plana, PASSWORD_DEFAULT);

    // 3. Preparar la consulta SQL (Previene Inyección SQL)
    $sql = "INSERT INTO Usuarios (nombre_usuario, correo_electronico, contrasena, fecha_nacimiento, tarjeta_bancaria, direccion_postal) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);

    // 4. "Atar" los parámetros
    // "ssssss" significa que estamos enviando 6 variables de tipo String
    mysqli_stmt_bind_param($stmt, "ssssss", 
        $nombre, 
        $correo, 
        $contrasena_hash, 
        $fecha_nacimiento, 
        $tarjeta, 
        $direccion
    );

    // 5. Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si fue exitoso
        $mensaje = "¡Cuenta creada con éxito! Ahora puedes iniciar sesión.";
    } else {
        // Si falló (ej. el correo ya existe, ya que es UNIQUE)
        $mensaje = "Error al crear la cuenta. Es posible que el correo electrónico ya esté en uso. " . mysqli_error($conn);
    }

    // 6. Cerrar la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// --- 2. CÓDIGO HTML (Mostrar la página y el formulario) ---
// Usamos la ruta '../' para "subir un nivel"
require_once '../includes/header.php'; 
?>

  <div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="text-center mb-4">Crear una Cuenta</h1>
            <p class="text-center">¡Únete a nuestra tienda! Llena tus datos para registrarte.</p>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form action="php/registro.php" method="POST" class="border p-4 rounded shadow-sm" style="background-color: #fff;">
                
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                </div>
                
                <div class="mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                </div>
                
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                </div>

                <div class="mb-3">
                    <label for="tarjeta_bancaria" class="form-label">Número de Tarjeta (Opcional)</label>
                    <input type="text" class="form-control" id="tarjeta_bancaria" name="tarjeta_bancaria" placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
                
                <div class="mb-3">
                    <label for="direccion_postal" class="form-label">Dirección Postal</label>
                    <textarea class="form-control" id="direccion_postal" name="direccion_postal" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>

            </form>
            
            <div class="text-center mt-3">
                <p>¿Ya tienes una cuenta? <a href="php/login.php">Inicia Sesión aquí</a>.</p>
            </div>

        </div>
    </div>
  </div>

<?php 
// Ruta para subir un nivel (..)
require_once '../includes/footer.php'; 
?>