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
  <title>Consulta almacen</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comconsalm.php" method="POST">
      <select name="idalmacen">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $almacenes = fetchRowsAssoc($conn, "SELECT NUM_ALMACEN, LOCALIDAD FROM ALMACEN;");
          genOptions($almacenes, "NUM_ALMACEN", "LOCALIDAD");
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

        $idalmacen = $_POST["idalmacen"];

        $prodalm = fetchRowsAssoc($conn, "SELECT producto.NOMBRE, almacen.LOCALIDAD, almacena.CANTIDAD FROM producto, almacen, almacena
                                          WHERE almacena.num_almacen = almacen.num_almacen
                                          AND almacena.id_producto = producto.id_producto
                                          AND almacena.num_almacen = \"$idalmacen\"");

        $conn = null;
        //Display message everything is correct
        genTable(["Producto", "Localidad", "Stock"], $prodalm);
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>