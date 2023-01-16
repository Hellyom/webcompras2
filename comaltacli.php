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
  <title>Alta cliente</title>
</head>
<body class="center-items">
  <div class="form-container">
    <form action="comaltacli.php" method="POST">
      <input name="nif" class="" type="text" placeholder="NIF" >
      <input name="nombre" class="" type="text" placeholder="Nombre" >
      <input name="apellido" class="" type="text" placeholder="Apellido" >
      <input name="cp" class="" type="text" placeholder="Codigo Postal" >
      <input name="direccion" class="" type="text" placeholder="Dirección" >
      <input name="ciudad" class="" type="text" placeholder="Ciudad" >
      <input class="" type="submit" value="Dar de alta">
    </form>
  </div>
  <?php
    try {
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Connection
        $conn = connecttoDB("localhost", "root", "rootroot", "comprasweb");

        //form values
        $nif = $_POST["nif"];
        $name = $_POST["nombre"];
        $surname = $_POST["apellido"];
        $cp = $_POST["cp"];
        $dir = $_POST["direccion"];
        $city = $_POST["ciudad"];


        //NIF validation
        $exp = "/\d{8}[A-Za-z]{1}/";
        if (!preg_match($exp, $nif)){
          createMessage("div", "message--error", "El NIF aportado no es válido");
          exit();
        }

        //Verify if NIF already exists
        $nifs = fetchRowsAssoc($conn, "SELECT NIF FROM CLIENTE");
        foreach($nifs as $n)
        if ($nif == $n["NIF"]){
          createMessage("div", "message--error", "El NIF aportado ya existe y no se ha da podido dar el alta");
          exit();
        }

        //Insert client to table
        insertRow($conn, "CLIENTE", ["NIF", "NOMBRE", "APELLIDO", "CP", "DIRECCION", "CIUDAD"],
                                    [$nif, $name, $surname, $cp, $dir, $city]);
        createMessage("div", "message", "El cliente se ha dado de alta correctamente");

        $conn = null;
      }
    }
    catch(PDOException $e) {
    createMessage("div", "message", $e->getMessage());
    }
  ?>
</body>
</html>