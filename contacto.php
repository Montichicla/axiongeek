<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <h1>Contacto</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $mensaje = $_POST['mensaje'];

        if (empty($nombre) || empty($email) || empty($mensaje)) {
            echo "<p>Por favor, completa todos los campos.</p>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p>Por favor, introduce un correo electrónico válido.</p>";
        } else {
            echo "<p>¡Gracias por comunicarte, estaremos en contacto en breve!</p>";
        }
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="mensaje">Mensaje:</label><br>
        <textarea id="mensaje" name="mensaje"></textarea><br>
        <input type="submit" value="Enviar">
    </form>

    <?php include 'includes/footer.php'; ?>
</body>
</html>