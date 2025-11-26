<?php
// php/producto_detalle.php

// 1. CONFIGURACIÓN
require_once '../includes/db_connect.php';
// (El header inicia la sesión y define BASE_URL)
require_once '../includes/header.php'; 

// 2. VALIDAR ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container py-5 text-center'><h3>Producto no especificado.</h3><a href='../index.php' class='btn btn-primary'>Volver</a></div>";
    require_once '../includes/footer.php';
    exit;
}

$id_producto = intval($_GET['id']);

// 3. CONSULTA A LA BASE DE DATOS
// Usamos JOIN para traer el nombre de la categoría también
$sql = "SELECT P.*, C.nombre_categoria 
        FROM Productos P 
        LEFT JOIN Categorias C ON P.id_categoria = C.id_categoria 
        WHERE P.id_producto = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_producto);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$producto = mysqli_fetch_assoc($result);

// Si no existe el producto
if (!$producto) {
    echo "<div class='container py-5 text-center'><h3>Producto no encontrado.</h3><a href='../index.php' class='btn btn-primary'>Volver</a></div>";
    require_once '../includes/footer.php';
    exit;
}
?>

<div class="container py-5">
    
    <div class="mb-4">
        <a href="../index.php" class="text-decoration-none text-muted">
            <iconify-icon icon="mdi:arrow-left"></iconify-icon> Volver al Catálogo
        </a>
    </div>

    <div class="row g-5">
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div style="background-color: #f9f9f9; padding: 20px; text-align: center;">
                    <img src="<?php echo BASE_URL . "/" . $producto['fotos']; ?>" 
                         class="img-fluid rounded-3" 
                         style="max-height: 500px; object-fit: contain;"
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            
            <span class="badge rounded-pill text-bg-light text-secondary border mb-2">
                <?php echo htmlspecialchars($producto['nombre_categoria'] ?? 'Sin Categoría'); ?>
            </span>

            <h1 class="display-5 fw-bold" style="font-family: 'Chilanka', cursive; color: #8B4513;">
                <?php echo htmlspecialchars($producto['nombre']); ?>
            </h1>

            <h2 class="text-primary my-3 fw-bold">$<?php echo number_format($producto['precio'], 2); ?></h2>

            <p class="lead text-muted mb-4">
                <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
            </p>
            
            <hr class="my-4" style="border-color: #9d6e64;">

            <div class="row mb-4 text-muted small">
                <div class="col-6">
                    <strong>Marca:</strong> <?php echo htmlspecialchars($producto['fabricante']); ?>
                </div>
                <div class="col-6">
                    <strong>Origen:</strong> <?php echo htmlspecialchars($producto['origen']); ?>
                </div>
                <div class="col-6 mt-2">
                    <strong>Disponibilidad:</strong> 
                    <?php if ($producto['cantidad_en_almacen'] > 0): ?>
                        <span class="text-success"><iconify-icon icon="mdi:check-circle"></iconify-icon> En Stock (<?php echo $producto['cantidad_en_almacen']; ?>)</span>
                    <?php else: ?>
                        <span class="text-danger"><iconify-icon icon="mdi:alert-circle"></iconify-icon> Agotado</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($producto['cantidad_en_almacen'] > 0): ?>
                
                <form action="php/carrito_agregar.php" method="POST" class="p-4 rounded-3" style="background-color: #FFF5F5; border: 1px solid #FADDE1;">
                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                    
                    <div class="row align-items-end">
                        <div class="col-4">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control text-center" value="1" min="1" max="<?php echo $producto['cantidad_en_almacen']; ?>">
                        </div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm">
                                <iconify-icon icon="mdi:cart-plus" class="me-2"></iconify-icon> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </form>

            <?php else: ?>
                <div class="d-grid">
                    <button class="btn btn-secondary btn-lg disabled" disabled>
                        Producto Agotado
                    </button>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>