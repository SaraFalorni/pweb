<?php
    include '../php/connectDB.php';

    $connection = new connectDB();
    $pdo = $connection->getPDO();

    try{

        $idRichiesta = $_POST['idRichiesta']; 
        $recensore = $_POST['recensore'];
        $recensito = $_POST['recensito'];
        $rating = $_POST['rating'];
        $textRec = $_POST['textRec'];

        $sql = "INSERT INTO Recensione (Recensore,Recensito,TestoRecensione,Rating,RichiestaRecensita) VALUES (:recensore,:recensito,:textRec,:rating,:idRichiesta)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recensore', $recensore);
        $statement->bindValue(':recensito', $recensito);
        $statement->bindValue(':textRec', $textRec);
        $statement->bindValue(':rating', $rating);
        $statement->bindValue(':idRichiesta', $idRichiesta);
        $statement->execute();
    }

    catch(PDOException | Exception $e) {
        $emess = $e->getMessage();
        $erroreinserimento = "C'è stato un errore nell'inserire la nuova recensione </br>";
        echo $erroreinserimento ;
        echo $emess;
    }

    $connection->close();
    $pdo = null;

?>