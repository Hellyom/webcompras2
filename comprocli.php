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
  <a class="cart-bar" href="checkout.php">
    Carrito  
    <p>
      <?php
        if(isset($_COOKIE["cart"])){
          $cartArray = json_decode($_COOKIE["cart"], true);

          echo count($cartArray);
        }
      ?>
    </p>
  </a>
  <div class="form-container">
    <form action="comprocli.php" method="POST">
      <select name="idproducto">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $productos = fetchRowsAssoc($conn, "SELECT ID_PRODUCTO, NOMBRE FROM PRODUCTO;");
          genOptions($productos, "ID_PRODUCTO", "NOMBRE");
          $conn = null;
        ?>
      </select>
      <input type="number" name="cantidad" min="1">
      <input class="" type="submit" value="Añadir al carrito">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        $idprod = $_POST["idproducto"];
        $quantity = intval($_POST["cantidad"]);

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

        //Enough stock, set cookies
        if($quantity <= $prodstock){
          
          //If there are already products in cart
          if(isset($_COOKIE["cart"])){
            $cartArray = json_decode($_COOKIE["cart"], true);
            $newItem = array($idprod => intval($quantity));

            $cartArray = array_merge($cartArray, $newItem);

            setcookie("cart", json_encode($cartArray));
          } else{ //If cookies dont exist
            $bite = array($idprod => intval($quantity));

            setcookie("cart", json_encode($bite));
          }
          
          //Update quantity in ALMACENA
          //updateRow($conn, "ALMACENA", ["CANTIDAD"], [$prodstock - $quantity], "WHERE ID_PRODUCTO = \"$idprod\"");

          //Insert row into COMPRA
          //nsertRow($conn, "COMPRA", ["NIF", "ID_PRODUCTO", "FECHA_COMPRA", "UNIDADES"],
          //                           [$nif, $idprod, date("Y-m-d"), $quantity]);
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