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

        $sql = "SELECT A.IDRisposta, B.IDRichiesta, B.Provincia, B.Regione, A.MessaggioRisposta, A.Utente AS UtenteRisposta,impr.Nome, impr.Descrizione,impr.Comune,impr.CognomeResponsabile, impr.NomeResponsabile,
                impr.ComuneNome, impr.Provincia, impr.Regione
                FROM Risposta A INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta 
                LEFT JOIN (select i.*, u.Nome as NomeResponsabile, u.Cognome as CognomeResponsabile, 
                c.Comune as ComuneNome, c.Provincia, c.Regione
                from impresa i inner join utente u on i.responsabile=u.userid
                inner join Comune c on i.Comune=c.id
                ) as impr
                on A.utente=impr.Responsabile
                WHERE Richiesta = :richiesta AND StatoRisposta <> 'rifiutata' ";
            
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':richiesta', $richiesta);
        $statement->execute();
        $row = $statement->fetch();
        if($row) {
            do {
                    echo ' <div id="divRisposta'.$row['IDRisposta'].'">'.
                    '<h4><b>Risposta</b> #'.$row['IDRisposta'].'</h4> '.
                    '<p><strong>Impresa</strong>: '.$row['Nome'].'</p>'. 
                    '<p><strong>Referente</strong>: '.$row['CognomeResponsabile'].', '.$row['NomeResponsabile'].'</p>'.
                    '<p><strong>Descrizione</strong>: '.$row['Descrizione'].'</p>'. 
                    '<p><strong>Localizzazione</strong>: '.$row['ComuneNome'].'/'.$row['Provincia'].'/'.$row['Regione'].'</p>'. 
                    '<p><strong>Messaggio per te</strong>: '.$row['MessaggioRisposta'].'</p>'.
                    '<input class=\'btn\' type = "button" id="bAccetta'. $row['IDRichiesta'] . '" value="Accetta" onclick="ChangeRispostaStatus( \'accettata\', '. $row['IDRichiesta'] . ' )"></input>&nbsp;' . 
                    '<input class=\'btn\' type = "button" id="bRifiuta'. $row['IDRichiesta'] . '" value="Rifiuta" onclick="ChangeRispostaStatus(\'rifiutata\', '. $row['IDRichiesta'] . ' )"></input>' . 
                    '<input class=\'btn\' type="hidden" id="IHRisposta'. $row['IDRichiesta'] . '" name="IHRisposta'. $row['IDRichiesta'] . '" value = "'.$row['IDRisposta'].'" > </input> '. 
                    '<p>Clicca <a href="checkRecensioni.php?utente='.$row['UtenteRisposta'].'">qui </a> per leggere le recensioni ricevute da '. $row['NomeResponsabile'].' '. $row['CognomeResponsabile'] . '</p> </div>' ;

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