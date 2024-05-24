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
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    
    // Insertar usuario
    $sql_usuario = "INSERT INTO usuarios (nombre, direccion, telefono) VALUES ('$nombre', '$direccion', '$telefono')";
    if ($conn->query($sql_usuario) === TRUE) {
        $usuario_id = $conn->insert_id;
    } else {
        die("Error al insertar usuario: " . $conn->error);
    }
    
    // Calcular el total
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    // Insertar pedido
    $fecha = date('Y-m-d H:i:s');
    $sql_pedido = "INSERT INTO pedidos (usuario_id, total, fecha) VALUES ('$usuario_id', '$total', '$fecha')";
    if ($conn->query($sql_pedido) === TRUE) {
        $pedido_id = $conn->insert_id;
    } else {
        die("Error al insertar pedido: " . $conn->error);
    }

    // Insertar detalles del pedido
    foreach ($productos as $producto) {
        $producto_id = $producto['id'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];
        $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio) VALUES ('$pedido_id', '$producto_id', '$cantidad', '$precio')";
        if ($conn->query($sql_detalle) !== TRUE) {
            die("Error al insertar detalle del pedido: " . $conn->error);
        }
    }

    // Limpiar el carrito
    $_SESSION['carrito'] = array();

    // Redirigir a la página de confirmación
    header('Location: confirmacion.php');
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

    <h1>Checkout</h1>

    <form action="checkout.php" method="post">
        <h2>Detalles de Facturación</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
        
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>
        
        <h2>Resumen del Pedido</h2>
        <ul>
            <?php $total = 0; ?>
            <?php foreach ($productos as $producto): ?>
                <li><?php echo $producto['nombre']; ?> (<?php echo $producto['cantidad']; ?>) - <?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?>€</li>
                <?php $total += $producto['precio'] * $producto['cantidad']; ?>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total: <?php echo number_format($total, 2); ?>€</strong></p>
        
        <button type="submit">Realizar Pedido</button>
    </form>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
