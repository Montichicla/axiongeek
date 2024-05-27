<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AxionGeek</title>
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
  <header>
    <div class="container">
      <div class="logo">
        <a href="index.php"><img src="images/logo.png" alt="Logo"></a>
      </div>
      <nav class="navigation">
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="productos.php">Productos</a></li>
          <li><a href="sobre_nosotros.php">Sobre nosotros</a></li>
          <li><a href="contacto.php">Contacto</a></li>
          <li><a href="carrito.php">Ver Carrito</a></li>
        </ul>
      </nav>
      <div class="search-bar">
        <form action="buscar.php" method="get">
          <input type="text" name="q" placeholder="Buscar productos">
          <button type="submit" name="submit">BUSCAR<i class="fas fa-search"></i></button>
        </form>
      </div>
      <div class="user-account">
        <a href="login.php"><i class="fas fa-user"></i></a>
      </div>
    </div>
  </header>
</body>
</html>