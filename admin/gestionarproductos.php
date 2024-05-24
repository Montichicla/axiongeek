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

// Consulta SQL para obtener todos los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos - Panel de Administración</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Gestionar Productos</h1>

    <a href="agregar_producto.php">Agregar Nuevo Producto</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Imagen</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td>$<?php echo $producto['precio']; ?></td>
                    <td><?php echo $producto['imagen']; ?></td>
       
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
