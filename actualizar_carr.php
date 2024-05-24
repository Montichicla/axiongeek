<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['id'];
    $cantidad = $_POST['cantidad'];

    if ($cantidad > 0) {
        $_SESSION['carrito'][$producto_id] = $cantidad;
    } else {
        unset($_SESSION['carrito'][$producto_id]);
    }
}

header('Location: carrito.php');
exit();
?>
