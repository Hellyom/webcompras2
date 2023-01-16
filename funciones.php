<?php

//Generate options for select// $value as what's in value and $showed as what'll be shower
//MAKE SURE value and showed IS CORRECT, CHECK FOR CAPS
function genOptions($array, $value, $showed){
    foreach($array as $row) {
        echo("<option value=".$row[$value].">". $row[$showed] . "</option>");
      }
}


//Returns a new PDO connection
function connecttoDB($servername, $username, $password, $dbname){
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
}

//Executes direct statement, used for simple and short statements
function execStatement($connection, $statement){
    $stmt = $connection->prepare($statement);
    $stmt->execute();
}

//Function to fetch results from a statement to a PDO
function fetchRowsAssoc($connection, $statement){
    $stmt = $connection->prepare($statement);
    $stmt->execute();
    
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
    
    return $result;
}

//Function to fetch results from a statement to a PDO
function fetchRowAssoc($connection, $statement){
    $stmt = $connection->prepare($statement);
    $stmt->execute();
    
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();
    
    return $result;
}


//Insert row in to table passing existing connection, table name, column names as array and values as array
function insertRow($connection, $table, $columns, $values){
    //INSERT INTO DPTO (COD_DPTO, NOMBRE) VALUES ("D001", "CONTABILIDAD")
    $sqlstatement = "INSERT INTO "."$table"." (";
    for ($i = 0; $i < count($columns); $i++){
        $sqlstatement .= $columns[$i];
        //Add comma if more than 1 column
        if (count($values) > 1 && $i != count($values) - 1){
            $sqlstatement .= ", ";
        }
    }
    $sqlstatement .= ") VALUES (";
    for ($i = 0; $i < count($values); $i++){
        $sqlstatement .= "\"" . $values[$i] . "\"";
        //Add coma if more than 1 value
        if (count($values) > 1 && $i != count($values) - 1){
            $sqlstatement .= ", ";
        }
    }
    $sqlstatement .= ");";
    $stmt = $connection->prepare($sqlstatement);
    $stmt->execute();
}

//connectino to db, $table to update, arrays of columns and values, condition for rows 
function updateRow($connection, $table, $columns, $values, $sqlcondition){
    $sqlstatement = "UPDATE $table ";

    $sqlstatement .= "SET ";

    for ($i = 0; $i < count($columns); $i++){
        $col = $columns[$i];
        $val = $values[$i];

        $sqlstatement .= "$col = $val";
        if (count($columns) > 1 && $i != count($columns) - 1){
            $sqlstatement .= ", ";
        }
    }

    $sqlstatement .= " $sqlcondition;";

    $stmt = $connection->prepare($sqlstatement);
    $stmt->execute();
}

//Creates an element with specified classe/s and message inside
function createMessage($element, $class, $message){
    echo("<$element class=\"$class\">");
        echo($message);
    echo("<$element/>");
}


//Generates table, $colnames and $rows are arrays
function genTable($colnames, $rows){
    echo("<table>");
        echo("<tr>");
        for($i = 0; $i < count($colnames); ++$i){
            echo("<td>");
                echo($colnames[$i]);
            echo("</td>");
        }
        echo("</tr>");

        foreach($rows as $row){
            echo("<tr>");
            foreach($row as $value){
                echo("<td>");
                    echo($value);
                echo("</td>");
            }
            echo("</tr>");
        }
    echo("</table>");
}


function userExists($conn, $username, $password){
    $sqlstatement = "SELECT USUARIO, CONTRASENA FROM CLIENTE
                     WHERE USUARIO = \"$username\"
                     AND CONTRASENA = \"$password\";";

    $stmt = $conn->prepare($sqlstatement);
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
        return true;
    } else {
        return false;
    }
}


function getUserToken($conn, $username){
    $sqlstatement = "SELECT tokens.token FROM TOKENS, CLIENTE
                     WHERE tokens.NIF = cliente.NIF
                     AND cliente.USUARIO = \"$username\";";

    $stmt = $conn->prepare($sqlstatement);
    $stmt->execute();

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();

    return $result["token"];
}

function getNifbyToken($conn, $token){
    $sqlstatement = "SELECT nif FROM TOKENS
                     WHERE tokens.token = \"$token\";";

    $stmt = $conn->prepare($sqlstatement);
    $stmt->execute();

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();

    return $result["nif"];
}
//<?php 
//include("funciones.php");
//
//$servername = "localhost";
//$username = "root";
//$password = "rootroot";
//$dbname = "empleadosnn";
//
//try {
//    $conn = connecttoDB($servername, $username, $password, $dbname);
//    $result = fetchRowsAssoc($conn, "SELECT * FROM DPTO");
//    genOptions($result, "COD_DPTO", "NOMBRE");
//}
//catch(PDOException $e) {
//    echo "Error: " . $e->getMessage();
//}
//
//$conn = null;
//FALTA CERRAR EL PHP
?>

