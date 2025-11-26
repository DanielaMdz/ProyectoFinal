<?php
// php/busqueda.php

// 1. CONFIGURACIÓN
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

$query = "";
$num_productos = 0;
$result = false;

// 2. LÓGICA DE BÚSQUEDA
if (isset($_GET['query'])) {
    $busqueda = trim($_GET['query']);
    $query = $busqueda;
    
    if ($busqueda != "") {
        // Buscamos en Nombre, Descripción o Marca
        $sql = "SELECT * FROM Productos 
                WHERE (nombre LIKE ? OR descripcion LIKE ? OR fabricante LIKE ?) 
                AND cantidad_en_almacen > 0";
        
        $param = "%" . $busqueda . "%";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $param, $param, $param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $num_productos = mysqli_num_rows($result);
    }
}
?>

<div class="container py-5">
    
    <div class="mb-4">
        <a href="../index.php" class="text-decoration-none text-muted">
            <iconify-icon icon="mdi:arrow-left"></iconify-icon> Volver al Catálogo
        </a>
    </div>

    <div class="section-header mb-5">
        <h2 class="display-4 fw-normal text-center" style="color: #8B4513; font-family: 'Chilanka', cursive;">
            Resultados de Búsqueda
        </h2>
        <p class="text-center text-muted fs-5">
            Buscando: "<strong><?php echo htmlspecialchars($query); ?></strong>"
        </p>
    </div>

    <?php if ($num_productos > 0): ?>
        <div class="row g-4">
            <?php while ($producto = mysqli_fetch_assoc($result)): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="producto_detalle.php?id=<?php echo $producto['id_producto']; ?>">
                            <div style="height: 250px; overflow: hidden; border-radius: 15px; background: #fff;">
                                <img src="<?php echo BASE_URL . "/" . $producto['fotos']; ?>" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 100%; object-fit: contain; padding: 10px;"
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            </div>
                        </a>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <a href="producto_detalle.php?id=<?php echo $producto['id_producto']; ?>" class="text-decoration-none text-dark">
                                <h6 class="card-title m-0"><?php echo htmlspecialchars($producto['nombre']); ?></h6>
                            </a>
                            <p class="text-muted small mb-2"><?php echo htmlspecialchars($producto['fabricante']); ?></p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <h5 class="text-primary mb-0">$<?php echo number_format($producto['precio'], 2); ?></h5>
                                
                                <form action="carrito_agregar.php" method="POST">
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="btn btn-primary rounded-circle p-0" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <iconify-icon icon="mdi:cart-plus" width="18"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-light text-center py-5 shadow-sm">
            <iconify-icon icon="mdi:emoticon-sad-outline" style="font-size: 4rem; color: #ccc;"></iconify-icon>
            <h3 class="mt-3 text-muted">No encontramos productos.</h3>
            <p>Intenta con otra palabra clave (ej. "Tónico", "Crema", "Sérum").</p>
            <a href="../index.php" class="btn btn-primary mt-3">Ver Todo el Catálogo</a>
        </div>
    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>