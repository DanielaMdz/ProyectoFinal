<?php
require_once 'includes/db_connect.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    die("<div class='container py-5 text-center'><h2>⛔ Acceso Denegado</h2><a href='index.php' class='btn btn-primary'>Volver</a></div>");
}

if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $accion = $_GET['accion'];

    if ($accion == 'borrar') {
        mysqli_query($conn, "DELETE FROM Carrito_de_compras WHERE id_producto = $id");
        if(mysqli_query($conn, "DELETE FROM Productos WHERE id_producto = $id")) {
            header("Location: admin_index.php?msg=borrado");
            exit;
        }
    } 
    elseif ($accion == 'subir_stock') {
        mysqli_query($conn, "UPDATE Productos SET cantidad_en_almacen = cantidad_en_almacen + 1 WHERE id_producto = $id");
        header("Location: admin_index.php"); exit;
    }
    elseif ($accion == 'bajar_stock') {
        mysqli_query($conn, "UPDATE Productos SET cantidad_en_almacen = cantidad_en_almacen - 1 WHERE id_producto = $id AND cantidad_en_almacen > 0");
        header("Location: admin_index.php"); exit;
    }
}

$sql_prod = "SELECT * FROM Productos ORDER BY id_producto DESC";
$res_prod = mysqli_query($conn, $sql_prod);

$sql_ventas = "SELECT H.*, U.nombre_usuario, P.nombre as nombre_producto 
               FROM Historial_de_compras H
               JOIN Usuarios U ON H.id_usuario = U.id_usuario
               LEFT JOIN Productos P ON H.id_producto = P.id_producto
               ORDER BY H.fecha_compra DESC";
$res_ventas = mysqli_query($conn, $sql_ventas);

if (!defined('BASE_URL')) define('BASE_URL', '/html/ProyectoFinal'); 
require_once 'includes/header.php'; 
?>

<style>
    :root { --color-cafe: #8B4513; --color-blanco: #FFFFFF; --color-fondo: #F9F3EC; }
    .card-admin { background: var(--color-blanco); border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(139, 69, 19, 0.08); overflow: hidden; margin-bottom: 2rem; }
    .table thead { background-color: var(--color-cafe); color: white; }
    .table thead th { background-color: var(--color-cafe); color: white; border: none; padding: 15px; font-weight: 400; }
    .table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
    .btn-cafe { background-color: var(--color-cafe); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
    .btn-cafe:hover { background-color: #5e2f0d; color: white; }
    .table-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    
    .btn-stock {
        background-color: #8B4513;
        color: white !important;
        width: 30px; 
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center; 
        justify-content: center;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-stock:hover {
        background-color: #5e2f0d;
    }

    /* Estilos recuperados para los botones de acción */
    .text-primary iconify-icon { color: #ea8791 !important; }
    .text-danger iconify-icon { color: #dc3545 !important; }
    .btn-light { background-color: #f8f9fa; border: 1px solid #eee; }
    .btn-light:hover { background-color: #e2e6ea; }
</style>

<div class="container pb-5 mt-4">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="display-5" style="font-family: 'Chilanka', cursive; color: #8B4513;">Panel de Control</h1>
            <p class="text-muted">Bienvenido, Admin.</p>
        </div>
        <a href="admin/agregar_producto.php" class="btn btn-cafe shadow-sm">
            <iconify-icon icon="mdi:plus-circle-outline" class="fs-5" style="vertical-align: sub;"></iconify-icon> Nuevo Producto
        </a>
    </div>

    <div class="card-admin">
        <div class="card-header bg-white border-0 pt-4 ps-4">
            <h4 class="m-0 text-muted"><iconify-icon icon="mdi:box-variant"></iconify-icon> Inventario de Productos</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover">
                    <thead>
                        <tr>
                            <th class="ps-4">Producto</th>
                            <th>Precio</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($prod = mysqli_fetch_assoc($res_prod)): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo BASE_URL . '/' . $prod['fotos']; ?>" 
                                         class="table-img" 
                                         onerror="this.src='https://via.placeholder.com/50'">
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($prod['nombre']); ?></span>
                                </div>
                            </td>
                            <td class="fw-bold" style="color: #8B4513;">
                                $<?php echo number_format($prod['precio'], 2); ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="admin_index.php?accion=bajar_stock&id=<?php echo $prod['id_producto']; ?>" 
                                       class="btn-stock text-white">
                                        <iconify-icon icon="mdi:minus" class="fs-6 text-white"></iconify-icon>
                                    </a>

                                    <span class="fw-bold fs-5 mx-2" style="color: #000000; min-width: 30px;">
                                        <?php echo $prod['cantidad_en_almacen']; ?>
                                    </span>

                                    <a href="admin_index.php?accion=subir_stock&id=<?php echo $prod['id_producto']; ?>" 
                                       class="btn-stock text-white">
                                        <iconify-icon icon="mdi:plus" class="fs-6 text-white"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <!-- Botones regresados al estilo original btn-light -->
                                <a href="admin/editar_producto.php?id=<?php echo $prod['id_producto']; ?>" 
                                   class="btn btn-sm btn-light text-primary rounded-circle" 
                                   title="Editar">
                                    <iconify-icon icon="mdi:pencil" class="fs-5"></iconify-icon>
                                </a>
                                
                                <a href="admin_index.php?accion=borrar&id=<?php echo $prod['id_producto']; ?>" 
                                   class="btn btn-sm btn-light text-danger rounded-circle" 
                                   onclick="return confirm('¿Eliminar este producto permanentemente?');"
                                   title="Eliminar">
                                    <iconify-icon icon="mdi:trash-can-outline" class="fs-5"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-admin">
        <div class="card-header bg-white border-0 pt-4 ps-4">
            <h4 class="m-0 text-muted"><iconify-icon icon="mdi:receipt-text-clock"></iconify-icon> Historial de Ventas Global</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th class="ps-4 text-dark bg-light">Fecha</th>
                            <th class="text-dark bg-light">Cliente</th>
                            <th class="text-dark bg-light">Producto</th>
                            <th class="text-dark bg-light text-center">Cant.</th>
                            <th class="text-dark bg-light text-end pe-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($res_ventas) > 0): ?>
                            <?php while ($venta = mysqli_fetch_assoc($res_ventas)): 
                                $total_linea = $venta['cantidad'] * $venta['precio_unitario_compra'];
                            ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?php echo date("d/m/Y H:i", strtotime($venta['fecha_compra'])); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <iconify-icon icon="carbon:user-avatar-filled" class="text-muted"></iconify-icon>
                                        <?php echo htmlspecialchars($venta['nombre_usuario']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($venta['nombre_producto']); ?></td>
                                <td class="text-center fw-bold text-dark"><?php echo $venta['cantidad']; ?></td>
                                <td class="text-end pe-4 fw-bold" style="color: #8B4513;">$<?php echo number_format($total_linea, 2); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Aún no hay ventas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php require_once 'includes/footer.php'; ?>