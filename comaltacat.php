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
  <title>Alta categoría</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="" method="POST">
      <input name="nomcategoria" class="" type="text" placeholder="Categoria" required>
      <input class="" type="submit" value="Dar de alta">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");
        //Fetching max ID and calculating next ID
        $lastID = fetchRowAssoc($conn, "SELECT MAX(ID_CATEGORIA) FROM categoria;")["MAX(ID_CATEGORIA)"];

        $newID = "C-" . (str_pad(intval(substr($lastID, 2)) + 1, 3, "0", STR_PAD_LEFT));
        $nomcat = $_POST["nomcategoria"];

        insertRow($conn, "categoria", ["ID_CATEGORIA", "NOMBRE"], [$newID, $nomcat]);
        $conn = null;

      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>