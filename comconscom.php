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
  <title>Consulta compras cliente</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comconscom.php" method="POST">
      <select name="nifcliente">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $nifclientes = fetchRowsAssoc($conn, "SELECT NIF FROM CLIENTE;");
          genOptions($nifclientes, "NIF", "NIF");
          $conn = null;
        ?>
      </select>
      <input type="date" name="fecha1">
      <input type="date" name="fecha2">
      <input class="" type="submit" value="Enviar">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        //date() transforms from m/d/y to y-m-d (same as mysql)
        $nif = $_POST["nifcliente"];
        $date1 = date($_POST["fecha1"]);
        $date2 = date($_POST["fecha2"]);

        $sales = fetchRowsAssoc($conn,  "SELECT compra.FECHA_COMPRA, producto.NOMBRE, producto.PRECIO, compra.UNIDADES FROM compra, producto
                                         WHERE compra.NIF = \"$nif\"
                                         AND compra.FECHA_COMPRA > \"$date1\"
                                         AND compra.FECHA_COMPRA < \"$date2\"
                                         AND compra.ID_PRODUCTO = producto.ID_PRODUCTO");

        //Calulate total cost of all purchases
        $total = 0;
        foreach($sales as $sale){
          $total += $sale["PRECIO"] * $sale["UNIDADES"];
        }

        //Generate tables
        genTable(["Fecha de compra", "Producto", "Precio", "Unidades"], $sales);
        createMessage("div", "message", "El total montante de las compras es $total");

        $conn = null;
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>