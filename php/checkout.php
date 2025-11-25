<?php
// php/checkout.php

// 1. CONFIGURACIÓN
session_start();
// Desactivar visualización de errores en pantalla para evitar que rompan el header()
// (Si falla, revisa el mensaje en el catch de abajo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db_connect.php';

// 2. SEGURIDAD
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// 3. INICIAR TRANSACCIÓN
mysqli_begin_transaction($conn);

try {
    // A. OBTENER PRODUCTOS DEL CARRITO
    $sql_cart = "SELECT C.id_producto, C.cantidad, P.precio, P.cantidad_en_almacen, P.nombre 
                 FROM Carrito_de_compras C 
                 JOIN Productos P ON C.id_producto = P.id_producto 
                 WHERE C.id_usuario = ?";
    
    $stmt = mysqli_prepare($conn, $sql_cart);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $resultado_carrito = mysqli_stmt_get_result($stmt);

    // Validar si está vacío
    if (mysqli_num_rows($resultado_carrito) == 0) {
        throw new Exception("Tu carrito está vacío.");
    }

    // ID del grupo de compra
    $id_compra_grupo = time(); 

    // B. PROCESAR CADA PRODUCTO
    while ($item = mysqli_fetch_assoc($resultado_carrito)) {
        
        $id_prod = $item['id_producto'];
        $cant = $item['cantidad'];
        $precio = $item['precio'];

        // Validar Stock
        if ($item['cantidad_en_almacen'] < $cant) {
            throw new Exception("Stock insuficiente para: " . $item['nombre']);
        }

        // 1. INSERTAR EN HISTORIAL
        $sql_hist = "INSERT INTO Historial_de_compras 
                    (id_compra, id_usuario, id_producto, cantidad, precio_unitario_compra) 
                    VALUES (?, ?, ?, ?, ?)";
        
        $stmt_hist = mysqli_prepare($conn, $sql_hist);
        // "iiiid" = int, int, int, int, decimal
        mysqli_stmt_bind_param($stmt_hist, "iiiid", $id_compra_grupo, $id_usuario, $id_prod, $cant, $precio);
        
        if (!mysqli_stmt_execute($stmt_hist)) {
            throw new Exception("Error al guardar historial: " . mysqli_error($conn));
        }

        // 2. RESTAR INVENTARIO
        $sql_inv = "UPDATE Productos SET cantidad_en_almacen = cantidad_en_almacen - ? WHERE id_producto = ?";
        $stmt_inv = mysqli_prepare($conn, $sql_inv);
        mysqli_stmt_bind_param($stmt_inv, "ii", $cant, $id_prod);
        
        if (!mysqli_stmt_execute($stmt_inv)) {
            throw new Exception("Error al actualizar inventario.");
        }
    }

    // C. VACIAR CARRITO
    $sql_borrar = "DELETE FROM Carrito_de_compras WHERE id_usuario = ?";
    $stmt_borrar = mysqli_prepare($conn, $sql_borrar);
    mysqli_stmt_bind_param($stmt_borrar, "i", $id_usuario);
    
    if (!mysqli_stmt_execute($stmt_borrar)) {
        throw new Exception("Error al vaciar el carrito.");
    }

    // D. CONFIRMAR TRANSACCIÓN
    mysqli_commit($conn);

    // Redirigir al historial
    header("Location: historial.php?compra=exito");
    exit;

} catch (Exception $e) {
    // E. SI ALGO FALLA, DESHACER CAMBIOS
    mysqli_rollback($conn);
    
    echo "<div style='text-align:center; padding:50px;'>";
    echo "<h2 style='color:red'>Error en la compra</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='carrito.php'>Volver al Carrito</a>";
    echo "</div>";
    exit;
}
?>