<?php
session_start();

// Se verifica si el usuario ya inició sesión, si es así, se redirige a la página de inicio
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}


$conn = new mysqli('localhost', 'nombreu', 'passw', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener las credenciales del formulario
    $usuario = $_POST['nombreu'];
    $contraseña = $_POST['passw'];

    // Consulta SQL para obtener la información del usuario
    $sql = "SELECT id, nombreu, passw FROM usuarios WHERE nombreu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($contraseña, $row['passw'])) {
            // Las credenciales son válidas, establecer sesión y redirigir
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario'] = $row['nombreu'];
            header('Location: index.php');
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
    <title>Iniciar Sesión - AxionGeek</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <h1>Iniciar Sesión</h1>

    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="nombreu">Usuario:</label>
        <input type="text" id="nombreu" name="nombreu" required>

        <label for="passw">Contraseña:</label>
        <input type="password" id="passw" name="passw" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
