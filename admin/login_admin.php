<?php
session_start();

// Verificar si el administrador ya ha iniciado sesión, si es así, redirigirlo al panel de administración
if (isset($_SESSION['admin'])) {
    header('Location: gestionar_productos.php');
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'nombreu', 'passw', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener las credenciales del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta SQL para obtener la información del administrador
    $sql = "SELECT * FROM administradores WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($contraseña, $row['contraseña'])) {
            // Las credenciales son válidas, establecer sesión y redirigir al panel de administración
            $_SESSION['admin'] = $usuario;
            header('Location: gestionar_productos.php');
            exit();
        } else {
            // Contraseña incorrecta
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Panel de Administración</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Iniciar Sesión como Administrador</h1>

    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login_admin.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>

        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
