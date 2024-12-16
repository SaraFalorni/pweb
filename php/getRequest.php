<?php 

include '../php/connectDB.php';
include '../php/getuser.php';
include '../php/class.richiesta.php';
include '../php/utilityFunctions.php';


$connection = new connectDB();
$pdo = $connection->getPDO();

try{

    $areatype = $_GET['areatype'];    

    $richiesteArea = new RichiesteArea($areatype, $currentuser);
    echo json_encode($richiesteArea); 
    //$richiesteArea->OutputRichiesteCerca();
    
}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nel cercare le richieste disponibili </br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>