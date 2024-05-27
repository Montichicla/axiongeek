<?php
session_start();

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: index.php');
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$carrito = $_SESSION['carrito'];
$productos = array();

foreach ($carrito as $id => $cantidad) {
    $sql = "SELECT * FROM productos WHERE id = $id";
    $result = $conn->query($sql);
    $producto = $result->fetch_assoc();
    $producto['cantidad'] = $cantidad;
    $productos[] = $producto;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Calcular el total
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    // Redirigir a la página de detalles de la compra
    header('Location: detalles_compra.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Axiongeek</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <h1>Gracias por tu compra</h1>

    <form action="vaciar_carrito.php" method="post">
        
        <h2>Resumen del Pedido</h2>
        <ul>
            <?php $total = 0; ?>
            <?php foreach ($productos as $producto): ?>
                <li><?php echo $producto['nombrep']; ?> (<?php echo $producto['cantidad']; ?>) - <?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?>€</li>
                <?php $total += $producto['precio'] * $producto['cantidad']; ?>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total: $<?php echo number_format($total, 2); ?> MXN</strong></p>
        
        <button type="submit">Seguir comprando</button>
    </form>

    <h1>Tu pedido se esta preparando</h1>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
