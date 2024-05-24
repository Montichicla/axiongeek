<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['id'];
    unset($_SESSION['carrito'][$producto_id]);
}

header('Location: carrito.php');
exit();
?>
