<?php 
require_once '../includes/db_connect.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Seguridad: Si no hay usuario, mandar al login
if (!isset($_SESSION['id_usuario'])) {
    // Usamos un script de JS para redirigir seguro
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_carrito = intval($_POST['id_carrito']);
    $accion = $_POST['accion'];

    if ($accion == 'borrar') {
        // Borrar producto
        $sql = "DELETE FROM Carrito_de_compras WHERE id_producto_en_carrito = ? AND id_usuario = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id_carrito, $id_usuario);
        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Producto eliminado del carrito.";
        }
        
    } elseif ($accion == 'actualizar') {
        // Actualizar cantidad
        $cantidad = intval($_POST['cantidad']);
        if ($cantidad > 0) {
            $sql = "UPDATE Carrito_de_compras SET cantidad = ? WHERE id_producto_en_carrito = ? AND id_usuario = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $cantidad, $id_carrito, $id_usuario);
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "✅ Cantidad actualizada.";
            }
        }
    }
    
}


$sql = "SELECT C.id_producto_en_carrito, C.cantidad, P.nombre, P.precio, P.fotos, P.id_producto 
        FROM Carrito_de_compras C
        JOIN Productos P ON C.id_producto = P.id_producto
        WHERE C.id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total_compra = 0;


require_once '../includes/header.php'; 
?>

<div class="container py-5">
    <h1 class="mb-4 text-center">Tu Carrito de Compras</h1>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-success text-center">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $subtotal = $row['precio'] * $row['cantidad'];
                        $total_compra += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo BASE_URL . "/" . $row['fotos']; ?>" width="50" class="rounded me-3">
                                <strong><?php echo htmlspecialchars($row['nombre']); ?></strong>
                            </div>
                        </td>
                        
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        
                        <td>
                            <form action="" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="accion" value="actualizar">
                                <input type="hidden" name="id_carrito" value="<?php echo $row['id_producto_en_carrito']; ?>">
                                
                                <input type="number" name="cantidad" value="<?php echo $row['cantidad']; ?>" min="1" class="form-control text-center me-2" style="width: 70px;">
                                
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Actualizar Cantidad">
                                    <iconify-icon icon="mdi:refresh"></iconify-icon>Actualizar
                                </button>
                            </form>
                        </td>
                        
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="accion" value="borrar">
                                <input type="hidden" name="id_carrito" value="<?php echo $row['id_producto_en_carrito']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>Borrar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-6 text-end">
                <h3>Total: $<?php echo number_format($total_compra, 2); ?></h3>
                
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-outline-secondary me-2">Seguir Comprando</a>
                
                <a href="php/checkout.php" class="btn btn-primary btn-lg">Finalizar Compra</a>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning text-center py-5">
            <h3>Tu carrito está vacío 🛒</h3>
            <p>¡Visita nuestro catálogo y añade tus productos favoritos!</p>
            <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-dark mt-3">Ir a la tienda</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>