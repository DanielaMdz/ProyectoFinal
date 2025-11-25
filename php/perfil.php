<?php
// php/perfil.php

// 1. CONFIGURACIÓN
require_once '../includes/db_connect.php';
session_start();

// 2. SEGURIDAD: Solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";
$tipo_mensaje = "";

// 3. LÓGICA DE ACTUALIZACIÓN (Si le da clic a "Guardar Cambios")
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre_usuario'];
    $nacimiento = $_POST['fecha_nacimiento'];
    $tarjeta = $_POST['tarjeta_bancaria'];
    $direccion = $_POST['direccion_postal'];

    // Actualizar datos en la BD
    $sql_update = "UPDATE Usuarios SET 
                   nombre_usuario = ?, 
                   fecha_nacimiento = ?, 
                   tarjeta_bancaria = ?, 
                   direccion_postal = ? 
                   WHERE id_usuario = ?";
    
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $nacimiento, $tarjeta, $direccion, $id_usuario);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "¡Datos actualizados correctamente!";
        $tipo_mensaje = "success";
        // Actualizar el nombre en la sesión también
        $_SESSION['nombre_usuario'] = $nombre;
    } else {
        $mensaje = "Error al actualizar: " . mysqli_error($conn);
        $tipo_mensaje = "danger";
    }
}

// 4. OBTENER DATOS DEL USUARIO (Para mostrar en el formulario)
$sql = "SELECT * FROM Usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($result);

// Incluimos el header al final para que las sesiones funcionen
require_once '../includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="text-center mb-4" style="color: #f2e1de;">Mi Perfil</h1>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header text-white p-4 text-center" style="background-color: #DEBAB1; border-radius: 15px 15px 0 0;">
                    <iconify-icon icon="carbon:user-avatar-filled" style="font-size: 4rem;"></iconify-icon>
                    <h3 class="m-0 mt-2">Hola, <?php echo htmlspecialchars($usuario['nombre_usuario']); ?></h3>
                    
                    <?php if ($usuario['es_admin'] == 1): ?>
                        <span class="badge bg-warning text-dark mt-2">Administrador</span>
                    <?php else: ?>
                        <span class="badge bg-light text-dark mt-2">Cliente</span>
                    <?php endif; ?>
                </div>

                <div class="card-body p-5" style="background-color: #f9d9d3ff">
                    <form action="" method="POST">
                        
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($usuario['correo_electronico']); ?>" readonly>
                            <small class="text-muted">El correo no se puede cambiar por seguridad.</small>  
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted fw-bold">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="fecha_nacimiento" value="<?php echo $usuario['fecha_nacimiento']; ?>">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted fw-bold">Tarjeta Bancaria</label>
                                <input type="text" class="form-control" name="tarjeta_bancaria" value="<?php echo htmlspecialchars($usuario['tarjeta_bancaria']); ?>" placeholder="XXXX-XXXX-XXXX-XXXX">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Dirección de Envío</label>
                            <textarea class="form-control" name="direccion_postal" rows="3"><?php echo htmlspecialchars($usuario['direccion_postal']); ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="../index.php" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5">Guardar Cambios</button>
                        </div>

                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="php/historial.php" class="text-decoration-none text-muted">
                    <iconify-icon icon="mdi:history"></iconify-icon> Ver mi historial de compras
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>