<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Índice WebVentas</title>
  <link rel="stylesheet" href="main.css">
</head>
<body class="center-items">
  <div class="account">
    <?php 
      if (!isset($_COOKIE["token"])){
        echo "<a href=\"comregcli.php\">Registrar cliente</a>";
        echo "<a href=\"comlogincli.php\">Iniciar Sesión</a>";
      } else {
        echo "<a href=\"comconscom.php\">Consulta Compras</a>";
        echo "<a href=\"comprocli.php\">Compro</a>";
      }
    ?>
  </div>
  <div class="index">
    <a href="comaltacat.php">Alta Categoría</a>
    <a href="comaltaalm.php">Alta Almacen</a>
    <a href="comaltacli.php">Alta Cliente</a>
    <a href="comaltapro.php">Alta Producto</a>
    <a href="comaprpro.php">Aprovisionar Producto</a>
    <a href="comconsalm.php">Consulta Almacen</a>
    <a href="comconstock.php">Consulta Stock</a>
  </div>
</body>
</html>