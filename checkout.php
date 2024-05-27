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

    // Verificar que los campos no estén vacíos
    if(empty($nombre) || empty($direccion) || empty($telefono)) {
        die("Todos los campos son obligatorios");
    }

    // Verificar que el nombre solo contenga letras
    if(!preg_match("/^[a-zA-Z ]*$/",$nombre)) {
        die("Solo se permiten letras y espacios en blanco en el nombre");
    }

    // Verificar que el teléfono solo contenga números
    if(!preg_match("/^[0-9]*$/",$telefono)) {
        die("Solo se permiten números en el teléfono");
    }

    // Calcular el total
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    // Insertar pedido
    $fecha = date('Y-m-d H:i:s');
    $sql_pedido = "INSERT INTO pedidos (nombre, direccion, telefono, total, fecha) VALUES ('$nombre', '$direccion', '$telefono', '$total', '$fecha')";
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
        $sql_detalle = "INSERT INTO detalles_pedido (producto_id, cantidad, precio) VALUES ( '$producto_id', '$cantidad', '$precio')";
        if ($conn->query($sql_detalle) !== TRUE) {
            die("Error al insertar detalle del pedido: " . $conn->error);
        }
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

    <h1>Confirma tu compra</h1>

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
                <li><?php echo $producto['nombrep']; ?> (<?php echo $producto['cantidad']; ?>) - <?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?>€</li>
                <?php $total += $producto['precio'] * $producto['cantidad']; ?>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total: $<?php echo number_format($total, 2); ?> MXN</strong></p>
        
        <button type="submit">Realizar Pedido</button>
    </form>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
