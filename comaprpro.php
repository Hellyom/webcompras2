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
  <title>Aprovisionar productos</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comaprpro.php" method="POST">
    <select name="almacen">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $localidades = fetchRowsAssoc($conn, "SELECT NUM_ALMACEN, LOCALIDAD FROM ALMACEN;");
          genOptions($localidades, "NUM_ALMACEN", "LOCALIDAD");
          $conn = null;
        ?>
      </select>
      <select name="producto">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $productos = fetchRowsAssoc($conn, "SELECT ID_PRODUCTO, NOMBRE FROM PRODUCTO;");
          genOptions($productos, "ID_PRODUCTO", "NOMBRE");
          $conn = null;
        ?>
      </select>
      <input name="numerounidades" class="" type="number" placeholder="Nº Unidades" required>
      <input class="" type="submit" value="Enviar">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        //Locality
        $alm = $_POST["almacen"];
        $prod = $_POST["producto"];
        $stock = $_POST["numerounidades"];

        fetchRowsAssoc($conn, "SELECT * FROM ALMACENA WHERE ID_PRODUCTO = \"P0001\"");

        insertRow($conn, "ALMACENA",  ["NUM_ALMACEN", "ID_PRODUCTO", "CANTIDAD"],
                                      [$alm         ,$prod         ,$stock     ]);

        $conn = null;
        //Display message everything is correct
        createMessage("div", "message", "Se ha aprovisionado correctamente");
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>