<?php 

include '../php/connectDB.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try{

    $idRic = $_POST['selRichiesta'];
    $idUser= $_POST['selUser'];
    $risp= $_POST['inRisposta'];
    $stato = 'inviata';

    //inserisce una nuova risposta 

    $sql = "INSERT INTO Risposta (MessaggioRisposta,StatoRisposta,Richiesta,Utente) VALUES (:risp, :stato, :idRic, :idUser) ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':risp', $risp);
    $statement->bindValue(':stato', $stato);
    $statement->bindValue(':idRic', $idRic);
    $statement->bindValue(':idUser', $idUser);
    $statement->execute();

    header("Location:LookForRequest.php");
    exit();
}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nell'inviare la risposta </br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>