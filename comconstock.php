<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include("funciones.php");
  ?>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css">
  <title>Consulta stock</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comconstock.php" method="POST">
      <select name="idproducto">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $productos = fetchRowsAssoc($conn, "SELECT ID_PRODUCTO, NOMBRE FROM PRODUCTO;");
          genOptions($productos, "ID_PRODUCTO", "NOMBRE");
          $conn = null;
        ?>
      </select>
      <input class="" type="submit" value="Enviar">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        //Product
        $idprod = $_POST["idproducto"];

        $prodalm = fetchRowsAssoc($conn, "SELECT producto.NOMBRE, almacen.LOCALIDAD, almacena.CANTIDAD FROM producto, almacen, almacena
                                          WHERE almacena.num_almacen = almacen.num_almacen
                                          AND almacena.id_producto = producto.id_producto
                                          AND almacena.id_producto = \"$idprod\"");

        $conn = null;
        //Display message everything is correct
        genTable(["Nombre", "Localidad", "Stock"], $prodalm);
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>