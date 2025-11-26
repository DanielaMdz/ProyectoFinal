<?php
// admin/agregar_producto.php

// 1. CONFIGURACIÓN Y SEGURIDAD
require_once '../includes/db_connect.php';
session_start();

// Verificar si es admin
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../index.php");
    exit;
}

$mensaje = '';
$tipo_mensaje = '';

// 2. PROCESAR FORMULARIO (Cuando le das a "Guardar")
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir datos
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad_en_almacen'];
    $fabricante = $_POST['fabricante'];
    $origen = $_POST['origen'];
    $id_categoria = $_POST['id_categoria'];
    
    // Procesar Imagen
    $foto = $_FILES['foto'];
    $ruta_db = ''; // Lo que guardaremos en la BD

    if ($foto['error'] == 0) {
        $nombre_archivo = "prod_" . time() . ".jpg"; // Nombre único
        $carpeta_destino = '../images/productos/';   // Carpeta física
        
        // Crear carpeta si no existe
        if (!is_dir($carpeta_destino)) mkdir($carpeta_destino, 0777, true);
        
        if (move_uploaded_file($foto['tmp_name'], $carpeta_destino . $nombre_archivo)) {
            // Guardamos la ruta relativa para que funcione con BASE_URL
            $ruta_db = 'images/productos/' . $nombre_archivo;
        } else {
            $mensaje = "Error al subir la imagen.";
            $tipo_mensaje = "danger";
        }
    }

    if (empty($mensaje)) {
        // Insertar en Base de Datos
        $sql = "INSERT INTO Productos (nombre, descripcion, fotos, precio, cantidad_en_almacen, fabricante, origen, id_categoria) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssdisii", $nombre, $descripcion, $ruta_db, $precio, $cantidad, $fabricante, $origen, $id_categoria);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "¡Producto agregado exitosamente!";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error SQL: " . mysqli_error($conn);
            $tipo_mensaje = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

// 3. OBTENER CATEGORÍAS (Para el select)
$sql_cat = "SELECT * FROM Categorias";
$result_cat = mysqli_query($conn, $sql_cat);

// Usamos la ruta ../ para salir de admin/ y entrar a includes/
require_once '../includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="display-6" style="color: #8B4513; font-family: 'Chilanka', cursive;">Nuevo Producto K-Beauty</h2>
                <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">
                    <iconify-icon icon="mdi:arrow-left"></iconify-icon> Volver
                </a>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> text-center shadow-sm rounded-3">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-3" style="background-color: #8B4513;">
                    <h5 class="m-0 fw-light">Detalles del Producto</h5>
                </div>
                
                <div class="card-body p-5 bg-white">
                    <form action="agregar_producto.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Nombre del Producto</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Ej. Ginseng Essence Water" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Marca / Fabricante</label>
                                <input type="text" class="form-control" name="fabricante" placeholder="Ej. Beauty of Joseon" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Precio ($)</label>
                                <input type="number" step="0.01" class="form-control" name="precio" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Stock Inicial</label>
                                <input type="number" class="form-control" name="cantidad_en_almacen" value="10" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Origen</label>
                                <input type="text" class="form-control" name="origen" value="Corea del Sur" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Categoría</label>
                            <select class="form-select" name="id_categoria" required>
                                <option value="">Selecciona una categoría...</option>
                                <?php while($cat = mysqli_fetch_assoc($result_cat)): ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>">
                                        <?php echo $cat['nombre_categoria']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" placeholder="Breve descripción de los beneficios..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Foto del Producto</label>
                            <input type="file" class="form-control" name="foto" accept="image/*" required>
                            <div class="form-text text-muted">Se recomienda formato cuadrado (JPG/PNG).</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: #8B4513;">
                                <iconify-icon icon="mdi:content-save-check"></iconify-icon> Guardar Producto
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>