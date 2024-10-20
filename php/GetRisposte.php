<?php 

include '../php/connectDB.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try{
    
    if(!isset($_COOKIE["user"])) {
        echo "cookie non settato </br>" ;
    }
    else {
        $user = $_COOKIE["user"];
        $richiesta = $_GET['richiesta'];
        $accettata = 'accettata';
        $rifiutata = 'rifiutata';

        $sql = "SELECT * FROM Risposta A INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta 
                WHERE Richiesta = :richiesta AND StatoRisposta <> 'rifiutata' ";
            
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':richiesta', $richiesta);
        $statement->execute();
        $row = $statement->fetch();
        if($row) {
            do {
                    echo ' <div id="divRisposta"> Risposta di ' . $row['Utente'] . 
                        ' per il montaggio del tuo mobile '.$row['TipoMobile'].' nella data '.$row['DataRichiesta'].
                        ' nella fascia oraria ' .$row['FasciaOraria']. ' nel comune di ' .$row['Comune']. ' </br> 
                        Il suo messaggio per te: '.$row['MessaggioRisposta'].
                       ' <input type = "button" id="bAccetta" value="Accetta" onclick="ChangeRispostaStatus( \'accettata\', '. $row['IDRichiesta'] . ' )"></input>' . 
                        '<input type = "button" id="bRifiuta" value="Rifiuta" onclick="ChangeRispostaStatus(\'rifiutata\', '. $row['IDRichiesta'] . ' )"></input>' . 
                        '<input type="hidden" id="IHRisposta" name="IHRisposta" value = "'.$row['IDRisposta'].'" > </input> </div>';

            } while( $row = $statement->fetch());
        }
        else {
            echo " </br> Nessuna risposta per la richiesta selezionata! </br> ";
        }
    }
}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nella ricerca delle risposte</br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>