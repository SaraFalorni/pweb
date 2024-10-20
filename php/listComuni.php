<?php 

include '../php/connectDB.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try{

      $provincia = $_GET['Provincia'];

        $sql = "SELECT id,Comune FROM Comune WHERE Provincia = :provincia";
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':provincia', $provincia);
        $statement->execute();
        $row = $statement->fetch();
        if($row) {
            do {
                 echo '<option value=' . $row['id'] . '>' . $row['Comune']. '</option>' ;
            } while( $row = $statement->fetch());
        }
}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nell'ottenere i comuni </br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>