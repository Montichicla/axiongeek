<?php
// Conectamos a la BD
$conn = new mysqli('localhost', 'root', '', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del producto
$producto_id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id = $producto_id";
$result = $conn->query($sql);
$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $producto['nombre']; ?> Axiongeek </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="producto-detalle">
    <img src= <?php echo $producto['imagen']; ?>>
        <h2><?php echo $producto['nombrep']; ?></h2>
        <p><?php echo $producto['descripcion']; ?></p>
        <p>$<?php echo $producto['precio']; ?></p>
        <form action="carrito.php" method="post">
            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
            <input type="number" name="cantidad" value="1" min="1">
            <button type="submit">Agregar al Carrito</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
