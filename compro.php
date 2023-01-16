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
  <title>Comprar artículos</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="compro.php" method="POST">
      <select name="idproducto">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $productos = fetchRowsAssoc($conn, "SELECT ID_PRODUCTO, NOMBRE FROM PRODUCTO;");
          genOptions($productos, "ID_PRODUCTO", "NOMBRE");
          $conn = null;
        ?>
      </select>
      <select name="nif">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $clientes = fetchRowsAssoc($conn, "SELECT NIF, NOMBRE FROM CLIENTE;");
          genOptions($clientes, "NIF", "NOMBRE");
          $conn = null;
        ?>
      </select>
      <input type="number" name="cantidad" min="1">
      <input class="" type="submit" value="Comprar">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        $idprod = $_POST["idproducto"];
        $quantity = intval($_POST["cantidad"]);
        $nif = $_POST["nif"];

        //Check if there is stock of the product 
        $prodstock = intval(fetchRowAssoc($conn, "SELECT CANTIDAD FROM ALMACENA
                                           WHERE ID_PRODUCTO = \"$idprod\"")["CANTIDAD"]);

        //If there's no stock
        if($prodstock == 0){
          createMessage("div", "message", "No tenemos stock de este producto, lo sentimos mucho");
          exit();
        }

        //Not enough stock
        if($quantity > $prodstock){
          createMessage("div", "message", "Solo nos quedan $prodstock unidades del producto, perdone las molestias");
          exit();
        }

        //Enough stock
        if($quantity <= $prodstock){
          //Update quantity in ALMACENA
          updateRow($conn, "ALMACENA", ["CANTIDAD"], [$prodstock - $quantity], "WHERE ID_PRODUCTO = \"$idprod\"");

          //Insert row into COMPRA
          insertRow($conn, "COMPRA", ["NIF", "ID_PRODUCTO", "FECHA_COMPRA", "UNIDADES"],
                                     [$nif, $idprod, date("Y-m-d"), $quantity]);
        }
        
        $conn = null;
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>