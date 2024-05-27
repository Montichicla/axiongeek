<?php
session_start();

// Vaciar el carrito
$_SESSION['carrito'] = array();

// Redirigir al usuario a la página de inicio
header('Location: index.php');
exit();
?>