<?php
session_start();

// Verificar si el usuario ya inició sesión, si es así, redirigirlo a la página de inicio
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'nombreu', 'passw', 'geekshop');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['pass'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Hashear la contraseña
    
    // Consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, usuario, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nombre, $usuario, $contraseña);
    
    if ($stmt->execute()) {
        // Registro exitoso, establecer sesión y redirigir
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit();
    } else {
        // Error al registrar al usuario
        $error = "Error al registrar al usuario. Por favor, inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AxionGeek</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
   
