<?php
session_start();

// Verificar si el usuario ha iniciado sesión como administrador
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'nombreu', 'passw', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario de agregar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    
    // Consulta SQL para insertar un nuevo producto
    $sql = "INSERT INTO productos (nombrep, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $nombrep, $precio, $descripcion, $imagen);

    if ($stmt->execute()) {
        $mensaje = "Producto agregado exitosamente.";
    } else {
        $error = "Error al agregar el producto. Por favor, inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto - Panel de Administración</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Agregar Producto</h1>

    <?php if (isset($mensaje)): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="agregar_producto.php" method="post">
        <label for="nombre">Nombre del producto:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required>

        <button type="submit">Agregar Producto</button>
    </form>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
