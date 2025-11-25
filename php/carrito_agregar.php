<?php
session_start();
require_once '../includes/db_connect.php';

// Validar sesión
if (!isset($_SESSION['id_usuario'])) {
    // Si no está logueado, guardar mensaje y mandar al login
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    // 1. Verificar si ya existe en el carrito
    $sql_check = "SELECT id_producto_en_carrito, cantidad FROM Carrito_de_compras 
                  WHERE id_usuario = ? AND id_producto = ?";
    $stmt = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        // ACTUALIZAR: Si ya existe, sumamos la cantidad
        $nueva_cant = $row['cantidad'] + $cantidad;
        $sql_update = "UPDATE Carrito_de_compras SET cantidad = ? WHERE id_producto_en_carrito = ?";
        $stmt_up = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_up, "ii", $nueva_cant, $row['id_producto_en_carrito']);
        mysqli_stmt_execute($stmt_up);
    } else {
        // INSERTAR: Si no existe, creamos la fila
        $sql_insert = "INSERT INTO Carrito_de_compras (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)";
        $stmt_in = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_in, "iii", $id_usuario, $id_producto, $cantidad);
        mysqli_stmt_execute($stmt_in);
    }

    // Redirigir al carrito para ver el resultado
    header("Location: carrito.php");
    exit;
}
?>