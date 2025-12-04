<?php
// admin/agregar_producto.php
// 1. CONTROL DE BÚFER Y ERRORES (CRUCIAL)
ob_start(); // Inicia el almacenamiento en búfer para prevenir errores de "Headers sent"
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Forzar excepciones de MySQL
error_reporting(E_ALL); // Reportar todo
ini_set('display_errors', 1); // Mostrar errores en pantalla

require_once '../includes/db_connect.php';

// Iniciar sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir BASE_URL si no existe
if (!defined('BASE_URL')) define('BASE_URL', '/html/ProyectoFinal');

// Verificar Admin
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

$mensaje = '';
$tipo_mensaje = '';

// 2. PROCESAR FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // A. Recibir y LIMPIAR datos (Casting explícito para evitar errores de tipo)
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $fabricante = isset($_POST['fabricante']) ? trim($_POST['fabricante']) : '';
    $origen = isset($_POST['origen']) ? trim($_POST['origen']) : 'Corea del Sur';
    
    // Convertir explícitamente a números para evitar errores SQL
    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.0;
    $cantidad = isset($_POST['cantidad_en_almacen']) ? (int)$_POST['cantidad_en_almacen'] : 0;
    $id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;

    // B. Validaciones
    if ($id_categoria === 0) {
        $mensaje = "Error: Debes seleccionar una categoría válida.";
        $tipo_mensaje = "danger";
    } elseif (empty($nombre) || $precio <= 0) {
        $mensaje = "Error: El nombre y el precio son obligatorios.";
        $tipo_mensaje = "danger";
    } else {
        // C. Procesar Imagen
        $foto = $_FILES['foto'] ?? null;
        $ruta_db = ''; 

        if ($foto && $foto['error'] == 0) {
            $nombre_archivo = "prod_" . time() . ".jpg"; 
            $carpeta_destino = '../images/productos/'; 
            
            // Verificar permisos y existencia de carpeta
            if (!is_dir($carpeta_destino)) {
                if (!mkdir($carpeta_destino, 0777, true)) {
                    $mensaje = "Error: No se pudo crear la carpeta de imágenes (Permisos denegados).";
                    $tipo_mensaje = "danger";
                }
            }
            
            if (empty($mensaje)) {
                if (move_uploaded_file($foto['tmp_name'], $carpeta_destino . $nombre_archivo)) {
                    $ruta_db = 'images/productos/' . $nombre_archivo;
                } else {
                    $mensaje = "Error al mover la imagen. Verifique permisos de carpeta.";
                    $tipo_mensaje = "danger";
                }
            }
        } else {
            $mensaje = "Error: La imagen es obligatoria.";
            $tipo_mensaje = "danger";
        }
    }

    // D. Insertar en BD
    if (empty($mensaje)) {
        try {
            // Asegurarnos que la conexión existe
            if (!isset($conn) || !$conn) throw new Exception("No hay conexión a la base de datos.");

            $sql = "INSERT INTO Productos (nombre, descripcion, fotos, precio, cantidad_en_almacen, fabricante, origen, id_categoria) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            
            if (!$stmt) throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));

            // Bind param con tipos ESTRICTOS:
            // s=string, s=string, s=string, d=double, i=int, s=string, s=string, i=int
            // NOTA: 'origen' es STRING ('s'), no INT ('i')
            mysqli_stmt_bind_param($stmt, "sssdissi", 
                $nombre, 
                $descripcion, 
                $ruta_db, 
                $precio, 
                $cantidad, 
                $fabricante, 
                $origen, 
                $id_categoria
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "¡Producto agregado exitosamente!";
                $tipo_mensaje = "success";
                // Limpiar POST para evitar reenvío al refrescar (opcional)
                $nombre = $descripcion = $fabricante = '';
                $precio = $cantidad = $id_categoria = 0;
            } else {
                throw new Exception("Error al ejecutar: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);

        } catch (mysqli_sql_exception $e) {
            $mensaje = "Error SQL: " . $e->getMessage();
            $tipo_mensaje = "danger";
        } catch (Throwable $e) { // Captura errores generales de PHP 7+
            $mensaje = "Error del Sistema: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}

// Cargar Categorías
$sql_cat = "SELECT * FROM Categorias";
$result_cat = mysqli_query($conn, $sql_cat);
if (!$result_cat) $mensaje = "Error cargando categorías: " . mysqli_error($conn);

require_once '../includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="display-6" style="color: #8B4513; font-family: 'Chilanka', cursive;">Nuevo Producto K-Beauty</h2>
                <a href="<?php echo BASE_URL; ?>/admin_index.php" class="btn btn-outline-secondary rounded-pill px-4">
                    <iconify-icon icon="mdi:arrow-left"></iconify-icon> Volver
                </a>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> text-center shadow-sm rounded-3">
                    <strong><?php echo ($tipo_mensaje == 'danger') ? '¡Ups!' : '¡Éxito!'; ?></strong> <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-3" style="background-color: #8B4513;">
                    <h5 class="m-0 fw-light">Detalles del Producto</h5>
                </div>
                
                <div class="card-body p-5 bg-white">
                    <form action="admin/agregar_producto.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Nombre del Producto</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Marca / Fabricante</label>
                                <input type="text" class="form-control" name="fabricante" value="<?php echo isset($_POST['fabricante']) ? htmlspecialchars($_POST['fabricante']) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Precio ($)</label>
                                <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo isset($_POST['precio']) ? $_POST['precio'] : ''; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Stock Inicial</label>
                                <input type="number" class="form-control" name="cantidad_en_almacen" value="<?php echo isset($_POST['cantidad_en_almacen']) ? $_POST['cantidad_en_almacen'] : '10'; ?>" required>
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
                                <?php 
                                if ($result_cat) {
                                    mysqli_data_seek($result_cat, 0); 
                                    while($cat = mysqli_fetch_assoc($result_cat)): 
                                ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>" <?php echo (isset($_POST['id_categoria']) && $_POST['id_categoria'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                                        <?php echo $cat['nombre_categoria']; ?>
                                    </option>
                                <?php 
                                    endwhile; 
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
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

<?php 
require_once '../includes/footer.php'; 
ob_end_flush(); // Enviar todo el contenido al navegador
?>