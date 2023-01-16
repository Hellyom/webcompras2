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
  <title>Registro Clientes</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comlogincli.php" method="POST">
      <input name="usuario" type="text" placeholder="Usuario" >
      <input name="contrasena" type="text" placeholder="Contraseña" >
      <input class="" type="submit" value="Inciar sesión">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        //form values
        $user = $_POST["usuario"];
        $pwd = $_POST["contrasena"];

        //Checking if credentials are correct
        if(userExists($conn, $user, $pwd)){
          setcookie("token", getUserToken($conn, $user));
        } else {
          createMessage("div", "message--error", "Inicio de sesión incorrecto");
          exit();
        }

        createMessage("div", "message", "Inicio de sesión correcto");
        $conn = null;
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>