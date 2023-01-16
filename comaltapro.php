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
  <title>Alta producto</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="" method="POST">
      <select name="idcategoria">
        <?php
          $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
          $categorias = fetchRowsAssoc($conn, "SELECT ID_CATEGORIA, NOMBRE FROM categoria;");
          genOptions($categorias, "ID_CATEGORIA", "NOMBRE");
          $conn = null;
        ?>
      </select>
      <input name="nomproducto" class="" type="text" placeholder="Nombre" required>
      <input name="precio" class="" type="text" placeholder="Precio" required>
      <input class="" type="submit" value="Dar de alta">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
        //Fetching max ID and calculating next ID
        $lastID = fetchRowAssoc($conn, "SELECT MAX(ID_PRODUCTO) FROM producto;")["MAX(ID_PRODUCTO)"];

        $newID = "P" . (str_pad(intval(substr($lastID, 1)) + 1, 4, "0", STR_PAD_LEFT));
        $idcat = $_POST["idcategoria"];
        $nomprod = $_POST["nomproducto"];
        $precio = $_POST["precio"];

        insertRow($conn, "producto", ["ID_PRODUCTO", "NOMBRE", "PRECIO", "ID_CATEGORIA"],
                                     [$newID       , $nomprod, $precio , $idcat]);

        //var_dump($newID);
        //var_dump($idcat);
        //var_dump($nomprod);
        //var_dump($precio);

        $conn = null;

      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>