<?php
// admin_index.php (En la raíz)

// 1. CONFIGURACIÓN
require_once 'includes/db_connect.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. SEGURIDAD: Solo Admins pueden entrar
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    // Usamos un estilo inline simple por si no cargan los estilos aún
    die("<div style='padding:50px; text-align:center; font-family:sans-serif; color:#8B4513;'>
            <h1 style='font-size:4rem;'>⛔</h1>
            <h2>Acceso Restringido</h2>
            <p>Esta zona es solo para administradores.</p>
            <a href='index.php' style='background:#8B4513; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Volver a la Tienda</a>
         </div>");
}

// 3. LÓGICA DE ACCIONES (BORRAR, SUBIR, BAJAR)
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $accion = $_GET['accion'];

    if ($accion == 'borrar') {
        mysqli_query($conn, "DELETE FROM Carrito_de_compras WHERE id_producto = $id");
        $sql_del = "DELETE FROM Productos WHERE id_producto = $id";
        if(mysqli_query($conn, $sql_del)) {
            header("Location: admin_index.php?msg=borrado");
            exit;
        }
    } 
    elseif ($accion == 'subir_stock') {
        $sql_up = "UPDATE Productos SET cantidad_en_almacen = cantidad_en_almacen + 1 WHERE id_producto = $id";
        mysqli_query($conn, $sql_up);
        header("Location: admin_index.php"); 
        exit;
    }
    elseif ($accion == 'bajar_stock') {
        $sql_down = "UPDATE Productos SET cantidad_en_almacen = cantidad_en_almacen - 1 WHERE id_producto = $id AND cantidad_en_almacen > 0";
        mysqli_query($conn, $sql_down);
        header("Location: admin_index.php"); 
        exit;
    }
}

// 4. CONSULTA DE INVENTARIO
$sql = "SELECT * FROM Productos ORDER BY id_producto DESC";
$result = mysqli_query($conn, $sql);

// Definir BASE_URL por seguridad
if (!defined('BASE_URL')) define('BASE_URL', '/html/ProyectoFinal'); 

// 5. INCLUIR HEADER (Aquí cargamos el menú de la tienda)
require_once 'includes/header.php'; 
?>

<style>
    :root {
        --color-cafe: #8B4513;
        --color-cafe-claro: #D4B2A0;
        --color-blanco: #FFFFFF;
    }

    /* Tarjeta Principal */
    .card-admin {
        background: var(--color-blanco);
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(139, 69, 19, 0.08);
        overflow: hidden;
    }

    /* Tabla Personalizada */
    .table thead {
        background-color: var(--color-cafe);
        color: white;
    }
    .table thead th {
        background-color: var(--color-cafe);
        color: white;
        border: none;
        font-weight: 400;
        padding: 15px;
        vertical-align: middle;
    }
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    /* Botones de Acción */
    .btn-cafe {
        background-color: var(--color-cafe);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        transition: 0.3s;
    }
    .btn-cafe:hover {
        background-color: #5e2f0d;
        color: white;
    }
    
    /* Botones de Stock (+ / -) */
    .btn-stock {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        background: white;
        color: var(--color-cafe);
        text-decoration: none;
        transition: 0.2s;
        font-size: 1.2rem;
        line-height: 0;
    }
    .btn-stock:hover {
        background-color: var(--color-cafe-claro);
        color: white;
        border-color: var(--color-cafe-claro);
    }

    /* Badges de Stock */
    .badge-stock {
        padding: 5px 10px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        min-width: 60px;
        display: inline-block;
        text-align: center;
    }
    .stock-ok { background-color: #E8F5E9; color: #2E7D32; }
    .stock-low { background-color: #FFEBEE; color: #C62828; }
</style>

<div class="container pb-5 mt-4">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="display-5" style="font-family: 'Chilanka', cursive; color: #8B4513;">Inventario</h1>
            <p class="text-muted">Panel de Control K-Beauty</p>
        </div>
        <a href="php/agregar_producto.php" class="btn btn-cafe shadow-sm">
            <iconify-icon icon="mdi:plus-circle-outline" class="fs-5" style="vertical-align: sub;"></iconify-icon> 
            Nuevo Producto
        </a>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'borrado'): ?>
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4 d-flex align-items-center gap-2">
            <iconify-icon icon="mdi:check-circle" class="fs-4"></iconify-icon>
            Producto eliminado correctamente.
        </div>
    <?php endif; ?>

    <div class="card-admin">
        <div class="table-responsive">
            <table class="table mb-0 table-hover">
                <thead>
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prod = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo BASE_URL . '/' . $prod['fotos']; ?>" class="table-img shadow-sm">
                                <div>
                                    <span class="d-block fw-bold text-dark"><?php echo htmlspecialchars($prod['nombre']); ?></span>
                                    <small class="text-muted"><?php echo $prod['fabricante']; ?></small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">ID: <?php echo $prod['id_categoria']; ?></span>
                        </td>

                        <td class="fw-bold" style="color: var(--color-cafe);">$<?php echo number_format($prod['precio'], 2); ?></td>
                        
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="admin_index.php?accion=bajar_stock&id=<?php echo $prod['id_producto']; ?>" 
                                   class="btn-stock shadow-sm" title="Restar">
                                    <iconify-icon icon="mdi:minus" style="font-size: 16px;"></iconify-icon>
                                </a>

                                <?php if ($prod['cantidad_en_almacen'] < 5): ?>
                                    <span class="badge-stock stock-low"><?php echo $prod['cantidad_en_almacen']; ?></span>
                                <?php else: ?>
                                    <span class="badge-stock stock-ok"><?php echo $prod['cantidad_en_almacen']; ?></span>
                                <?php endif; ?>

                                <a href="admin_index.php?accion=subir_stock&id=<?php echo $prod['id_producto']; ?>" 
                                   class="btn-stock shadow-sm" title="Sumar">
                                    <iconify-icon icon="mdi:plus" style="font-size: 16px;"></iconify-icon>
                                </a>
                            </div>
                        </td>
                        
                        <td class="text-end pe-4">
                            <a href="admin_index.php?accion=borrar&id=<?php echo $prod['id_producto']; ?>" 
                               class="btn btn-sm btn-light text-danger border-0 rounded-circle"
                               title="Eliminar Producto"
                               style="width: 35px; height: 35px; line-height: 35px;"
                               onclick="return confirm('¿Estás seguro de eliminar permanentemente a: <?php echo $prod['nombre']; ?>?');">
                                <iconify-icon icon="mdi:trash-can-outline" class="fs-5"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="text-center py-5">
                <iconify-icon icon="mdi:package-variant-closed" class="fs-1 text-muted mb-3"></iconify-icon>
                <h4 class="text-muted">Tu inventario está vacío</h4>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php 
// Incluir Footer
require_once 'includes/footer.php'; 
?>