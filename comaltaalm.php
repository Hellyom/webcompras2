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
  <title>Alta almacen</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comaltaalm.php" method="POST">
      <input name="localidad" class="" type="text" placeholder="Localidad" required>
      <input class="" type="submit" value="Dar de alta">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        //Locality
        $loc = $_POST["localidad"];

        insertRow($conn, "almacen",  ["LOCALIDAD"],
                                     [$loc     ]);

        $conn = null;
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>