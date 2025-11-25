<?php
// 1. INCLUIR ARCHIVOS Y SEGURIDAD
require_once '../includes/header.php'; 
require_once '../includes/db_connect.php';

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>window.location.href='" . BASE_URL . "/php/login.php';</script>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// 2. OBTENER LAS COMPRAS (AGRUPADAS POR ID_COMPRA)
// Primero obtenemos los IDs únicos de compra y sus fechas
$sql_compras = "SELECT DISTINCT id_compra, fecha_compra 
                FROM Historial_de_compras 
                WHERE id_usuario = ? 
                ORDER BY fecha_compra DESC";
$stmt = mysqli_prepare($conn, $sql_compras);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$result_compras = mysqli_stmt_get_result($stmt);
?>

<div class="container py-5">
    <h1 class="text-center mb-4">📜 Tu Historial de Compras</h1>

    <?php if (isset($_GET['compra']) && $_GET['compra'] == 'exito'): ?>
        <div class="alert alert-success text-center mb-5">
            <h3>¡Gracias por tu compra! 🎉</h3>
            <p>Tu pedido ha sido procesado exitosamente y ya aparece en tu historial.</p>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result_compras) > 0): ?>
        
        <div class="accordion" id="historialAccordion">
            <?php 
            // Recorremos cada "Ticket" o "Pedido"
            while ($compra = mysqli_fetch_assoc($result_compras)): 
                $id_compra = $compra['id_compra'];
                $fecha = date("d/m/Y H:i", strtotime($compra['fecha_compra']));
                
                // CONSULTA INTERNA: Obtener los productos de ESTA compra específica
                $sql_detalles = "SELECT H.cantidad, H.precio_unitario_compra, P.nombre, P.fotos 
                                 FROM Historial_de_compras H
                                 JOIN Productos P ON H.id_producto = P.id_producto
                                 WHERE H.id_compra = ?";
                $stmt_detalles = mysqli_prepare($conn, $sql_detalles);
                mysqli_stmt_bind_param($stmt_detalles, "i", $id_compra);
                mysqli_stmt_execute($stmt_detalles);
                $result_detalles = mysqli_stmt_get_result($stmt_detalles);
                
                // Calcular total de este pedido
                $total_pedido = 0;
            ?>

            <div class="accordion-item mb-3 border rounded shadow-sm">
                <h2 class="accordion-header" id="heading<?php echo $id_compra; ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $id_compra; ?>" aria-expanded="false" aria-controls="collapse<?php echo $id_compra; ?>">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <span><strong>Pedido #<?php echo $id_compra; ?></strong></span>
                            <span class="text-muted"><iconify-icon icon="uiw:date"></iconify-icon> <?php echo $fecha; ?></span>
                        </div>
                    </button>
                </h2>
                <div id="collapse<?php echo $id_compra; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $id_compra; ?>" data-bs-parent="#historialAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cant.</th>
                                        <th>Precio Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = mysqli_fetch_assoc($result_detalles)): 
                                        $subtotal = $item['cantidad'] * $item['precio_unitario_compra'];
                                        $total_pedido += $subtotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo BASE_URL . "/" . $item['fotos']; ?>" width="40" class="rounded me-2">
                                                <?php echo htmlspecialchars($item['nombre']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo $item['cantidad']; ?></td>
                                        <td>$<?php echo number_format($item['precio_unitario_compra'], 2); ?></td>
                                        <td class="text-end">$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                        <td class="text-end text-primary fw-bold fs-5">$<?php echo number_format($total_pedido, 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <div class="alert alert-secondary text-center py-5">
            <h4>Aún no has realizado ninguna compra.</h4>
            <p>¡Visita nuestro catálogo y encuentra tus favoritos!</p>
            <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-primary mt-3">Ir al Catálogo</a>
        </div>
    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>