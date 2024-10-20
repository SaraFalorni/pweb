<?php 

include '../php/connectDB.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try{

    $stato = $_POST['stato'];
    $idRisp = $_POST['idRisp'];
    $idRic = $_POST['idRic'];

    $sql = "UPDATE Risposta  SET StatoRisposta = :stato WHERE IDRisposta = :idRisp";
    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':stato', $stato);
    $statement->bindValue( ':idRisp', $idRisp);
    $statement->execute();

    //se la risposta è accettata va aggiornata anche lo stato della rispettiva richiesta
    if($stato == 'accettata') {
        $presaInCarico = ' presa in carico';
        $sql = "UPDATE Richiesta  SET StatoRichiesta = :stato, RispostaAccettata = :idRisp WHERE IDRichiesta = :idRic";
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':stato', $presaInCarico);
        $statement->bindValue( ':idRic', $idRic);
        $statement->bindValue( ':idRisp', $idRisp);
        $statement->execute();
    }
    
}  

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nel cambiare lo stato della risposta </br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>