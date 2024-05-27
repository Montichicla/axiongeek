<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['id'];
    $cantidad = $_POST['cantidad'];

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id] += $cantidad;
    } else {
        $_SESSION['carrito'][$producto_id] = $cantidad;
    }
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$productos = array();

foreach ($carrito as $id => $cantidad) {
    $sql = "SELECT * FROM productos WHERE id = $id";
    $result = $conn->query($sql);
    $producto = $result->fetch_assoc();
    $producto['cantidad'] = $cantidad;
    $productos[] = $producto;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Axiongeek</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <h1>Carrito de Compras</h1>

    <?php if (empty($productos)): ?>
        <p>Tu carrito está vacío.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['nombrep']; ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>
                            <form action="actualizar_carr.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" min="1">
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?php echo   number_format($producto['precio'] * $producto['cantidad'], 2); ?> MXN</td>
                        <td>
                            <form action="eliminar_carr.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php $total += $producto['precio'] * $producto['cantidad']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Total: <?php echo number_format($total, 2); ?>€</strong></p>
        <a href="checkout.php">Proceder al Pago</a>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

