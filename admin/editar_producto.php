<?php
// admin/editar_producto.php

// 1. CONFIGURACIÓN Y SEGURIDAD
require_once '../includes/db_connect.php';
session_start();

// Definir BASE_URL por si el header aún no carga
if (!defined('BASE_URL')) define('BASE_URL', '/html/ProyectoFinal');

// Verificar si es admin
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

// Validar ID
if (!isset($_GET['id'])) { 
    header("Location: " . BASE_URL . "/admin_index.php"); 
    exit; 
}
$id_producto = intval($_GET['id']);

// 2. PROCESAR FORMULARIO (GUARDAR CAMBIOS)
$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $fabricante = $_POST['fabricante'];
    $origen = $_POST['origen'];
    $id_categoria = $_POST['id_categoria'];
    
    // Lógica de Imagen
    $ruta_final = $_POST['ruta_actual']; // Conservar la vieja por defecto
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $nombre_archivo = "prod_" . time() . ".jpg";
        $carpeta = '../images/productos/';
        
        // Asegurar que la carpeta exista
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $carpeta . $nombre_archivo)) {
            $ruta_final = 'images/productos/' . $nombre_archivo;
        }
    }

    // Actualizar en BD
    $sql = "UPDATE Productos SET nombre=?, descripcion=?, precio=?, cantidad_en_almacen=?, fabricante=?, origen=?, id_categoria=?, fotos=? WHERE id_producto=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // --- ¡AQUÍ ESTABA EL ERROR! CORREGIDO ---
    // Antes: "ssdisSisi" -> Ahora: "ssdisissi"
    mysqli_stmt_bind_param($stmt, "ssdisissi", $nombre, $descripcion, $precio, $cantidad, $fabricante, $origen, $id_categoria, $ruta_final, $id_producto);
    
    if (mysqli_stmt_execute($stmt)) {
        // ¡ÉXITO! Redirigir al panel de admin
        header("Location: " . BASE_URL . "/admin_index.php?msg=editado");
        exit;
    } else {
        $mensaje = "Error: " . mysqli_error($conn);
    }
}

// OBTENER DATOS ACTUALES
$sql_p = "SELECT * FROM Productos WHERE id_producto = $id_producto";
$res_p = mysqli_query($conn, $sql_p);
$prod = mysqli_fetch_assoc($res_p);

// OBTENER CATEGORÍAS
$res_cat = mysqli_query($conn, "SELECT * FROM Categorias");

// Incluir Header (Usamos ruta física ../ para salir de la carpeta admin)
require_once '../includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="text-center mb-4" style="color: #8B4513; font-family: 'Chilanka';">Editar Producto</h2>
            
            <?php if ($mensaje): ?>
                <div class="alert alert-danger text-center"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg rounded-4 p-4 bg-white">
                
                <form action="<?php echo BASE_URL; ?>/admin/editar_producto.php?id=<?php echo $id_producto; ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($prod['nombre']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Marca</label>
                            <input type="text" class="form-control" name="fabricante" value="<?php echo htmlspecialchars($prod['fabricante']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Precio ($)</label>
                            <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $prod['precio']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Stock</label>
                            <input type="number" class="form-control" name="cantidad" value="<?php echo $prod['cantidad_en_almacen']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="id_categoria">
                                <?php while($cat = mysqli_fetch_assoc($res_cat)): ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>" <?php if($cat['id_categoria'] == $prod['id_categoria']) echo 'selected'; ?>>
                                        <?php echo $cat['nombre_categoria']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($prod['descripcion']); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Imagen Actual</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded">
                            <img src="<?php echo BASE_URL . '/' . $prod['fotos']; ?>" width="80" class="rounded">
                            
                            <div class="flex-grow-1">
                                <label class="small text-muted">Cambiar imagen (Opcional):</label>
                                <input type="file" class="form-control form-control-sm" name="foto" accept="image/*">
                            </div>
                        </div>
                        <input type="hidden" name="ruta_actual" value="<?php echo $prod['fotos']; ?>">
                    </div>
                    
                    <input type="hidden" name="origen" value="<?php echo $prod['origen']; ?>">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" style="background-color: #8B4513; border:none;">Guardar Cambios</button>
                        
                        <a href="<?php echo BASE_URL; ?>/admin_index.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>