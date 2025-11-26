<style>
    :root {
        --color-fondo: #F9F3EC;
        --color-cafe: #8B4513;
        --color-blanco: #FFFFFF;
    }

    body { 
        font-family: 'Montserrat', sans-serif; 
        background-color: var(--color-fondo);
    }

    /* Tarjeta Principal */
    .card-admin {
        background: var(--color-blanco);
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(139, 69, 19, 0.08);
        overflow: hidden;
    }

    /* Tabla */
    .table thead { background-color: var(--color-cafe); color: white; }
    .table tbody td { vertical-align: middle; }
    
    /* --- ARREGLO DE BOTONES --- */
    
    /* Botón Nuevo Producto (Café Sólido) */
    .btn-cafe {
        background-color: #8B4513 !important;
        color: #FFFFFF !important;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
    }
    .btn-cafe iconify-icon { color: #FFFFFF !important; } /* Forzar ícono blanco */

    /* Botones de Acción (Editar/Borrar) */
    .btn-outline-danger { border-color: #dc3545; color: #dc3545; }
    .btn-outline-danger:hover { background-color: #dc3545; color: white; }
    
    /* Asegurar que los íconos de acción tengan su color correcto */
    .text-danger iconify-icon { color: #dc3545 !important; }
    .text-primary iconify-icon { color: #0d6efd !important; }
    
    /* Botones de Stock (+/-) */
    .btn-stock {
        background: white;
        border: 1px solid #ccc;
        color: #8B4513 !important; /* Íconos café */
        width: 30px; height: 30px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        text-decoration: none;
    }
    .btn-stock iconify-icon { color: #8B4513 !important; }

    
</style>
<?php
// admin_index.php (En la raíz)

// 1. CONFIGURACIÓN
require_once 'includes/db_connect.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. SEGURIDAD
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    die("<div class='container py-5 text-center'><h2>⛔ Acceso Denegado</h2><a href='index.php' class='btn btn-primary'>Volver</a></div>");
}

// 3. LÓGICA DE ACCIONES (BORRAR, SUBIR STOCK, BAJAR STOCK)
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

// 4. CONSULTAS
// A) Inventario
$sql_prod = "SELECT * FROM Productos ORDER BY id_producto DESC";
$res_prod = mysqli_query($conn, $sql_prod);

// B) Historial de Ventas Global (Uniendo tablas para ver nombres)
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
                                    <img src="<?php echo BASE_URL . '/' . $prod['fotos']; ?>" class="table-img">
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($prod['nombre']); ?></span>
                                </div>
                            </td>
                            <td class="fw-bold" style="color: #8B4513;">$<?php echo number_format($prod['precio'], 2); ?></td>
                            <td class="text-center">
                                <a href="admin_index.php?accion=bajar_stock&id=<?php echo $prod['id_producto']; ?>" class="text-decoration-none text-secondary"><iconify-icon icon="mdi:minus-circle" class="fs-5"></iconify-icon></a>
                                <span class="badge <?php echo ($prod['cantidad_en_almacen'] < 5) ? 'bg-danger' : 'bg-success'; ?> mx-2 rounded-pill"><?php echo $prod['cantidad_en_almacen']; ?></span>
                                <a href="admin_index.php?accion=subir_stock&id=<?php echo $prod['id_producto']; ?>" class="text-decoration-none text-secondary"><iconify-icon icon="mdi:plus-circle" class="fs-5"></iconify-icon></a>
                            </td>
                            <td class="text-end pe-4">
                                <a href="admin/editar_producto.php?id=<?php echo $prod['id_producto']; ?>" class="btn btn-sm btn-light text-primary rounded-circle" title="Editar">
                                    <iconify-icon icon="mdi:pencil" class="fs-5"></iconify-icon>
                                </a>
                                <a href="admin_index.php?accion=borrar&id=<?php echo $prod['id_producto']; ?>" class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('¿Eliminar?');">
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
                                <td class="text-center"><?php echo $venta['cantidad']; ?></td>
                                <td class="text-end pe-4 fw-bold text-success">$<?php echo number_format($total_linea, 2); ?></td>
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