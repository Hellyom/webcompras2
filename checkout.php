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
  <title>Checkout</title>
</head>
<body class="center-items">

<form action="checkout.php" method="POST">
  <div class="products">
    <?php
    if (isset($_COOKIE["cart"])){
      $products = json_decode($_COOKIE["cart"]);

      $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

      foreach($products as $id => $q){
        echo "<div>";
          echo (fetchRowAssoc($conn, "SELECT nombre FROM PRODUCTO WHERE ID_PRODUCTO = \"$id\"")["nombre"]) . " == " . $q;
          echo ("<input name=\"ids[]\" value=\"$id\" hidden>");
          echo ("<input name=\"quantities[]\" value=$q hidden>");
        echo "</div>";
      }
      $conn = null;
    } else {
      echo "No hay articulos en la cesta";
    } 
    ?>
  </div>
  <input type="submit" value="Comprar">
</form>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        for($i = 0; $i < count($_POST["ids"]); $i++){
          $idprod = $_POST["ids"][$i];
          $quantity = intval($_POST["quantities"][$i]);
          //Check if there is stock of the product 
          $prodstock = intval(fetchRowAssoc($conn, "SELECT CANTIDAD FROM ALMACENA
                                            WHERE ID_PRODUCTO = \"$idprod\"")["CANTIDAD"]);

          $prodName = fetchRowAssoc($conn, "SELECT nombre FROM PRODUCTO
                                            WHERE ID_PRODUCTO = \"$idprod\"")["nombre"];

          //If there's no stock
          if($prodstock == 0){
          createMessage("div", "message", "No tenemos stock del producto $prodName, lo sentimos mucho");
          exit();
          }

          //Not enough stock
          if($quantity > $prodstock){
          createMessage("div", "message", "Solo nos quedan $prodstock unidades del producto $prodName, perdone las molestias");
          exit();
          }

          //Update quantity in ALMACENA
          updateRow($conn, "ALMACENA", ["CANTIDAD"], [$prodstock - $quantity], "WHERE ID_PRODUCTO = \"$idprod\"");
            
          //Insert row into COMPRA
          $nif = getNifbyToken($conn, $_COOKIE["token"]);

          insertRow($conn, "COMPRA", ["NIF", "ID_PRODUCTO", "FECHA_COMPRA", "UNIDADES"],
          [$nif, $idprod, date("Y-m-d"), $quantity]);

          //Delete cart
          setcookie("cart", "", time() - 3600);
        }
      }  
        $conn = null;
    }

    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>