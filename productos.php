<?php
//Conectamos la BD
$conn = new mysqli('localhost', 'root', '', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="productos">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='producto'>";
                echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombrep'] . "'>";                
                echo "<h3>" . $row['nombrep'] . "</h3>";
                echo "<p>Categoría: " . $row['categoria'] . "</p>"; // Línea agregada
                echo "<p>" . $row['descripcion'] . "</p>";
                echo "<p>$" . $row['precio'] . "</p>";
                echo "<a href='producto.php?id=" . $row['id'] . "'>Ver Producto</a>";
                echo "</div>";
            }
        } else {
            echo "No hay productos disponibles.";
        }
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>