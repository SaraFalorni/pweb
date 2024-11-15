<?php

/* Presume che la include di connectDB sia già stata fatta */
$connection = new connectDB();
$pdo = $connection->getPDO();
$currentuser="-";
try{
    if(!isset($_COOKIE["usertype"])) {
        echo "cookie usertype non settato </br>" ;
    }
    else {
        $usertype = $_COOKIE["usertype"]; }   
    
    if(!isset($_COOKIE["user"])) 
    {
       echo "la navigazione anonima non è autorizzata su questa pagina </br>";
      /* *** */
      }
    else
    {
      $currentuser=$_COOKIE["user"];
    }
  }
    catch(PDOException | Exception $e) {
      $emess = $e->getMessage();
      $erroreinserimento = "C'è stato un errore </br>";
      echo $erroreinserimento ;
      echo $emess;
  }

?>