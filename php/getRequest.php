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
    $area = $_GET['area'];
   
    $sql = 0;

    if($area == 'Regione') {
        $sql = "SELECT * 
                FROM Richiesta R                          
                WHERE  R.Regione = (SELECT Regione
                                    FROM Comune C 
                                    WHERE C.id = (SELECT Comune
                                                  FROM Impresa
                                                  WHERE Responsabile = :utente))
                        AND NOT EXISTS (SELECT *
                                        FROM Risposta T
                                        WHERE R.IDRichiesta = T.Richiesta AND T.Utente = :utente1) 
                        AND StatoRichiesta = 'inviata' ";
    }
    else if($area == 'Provincia') {
        $sql = "SELECT * 
                FROM Richiesta R                          
                WHERE  R.Provincia = (SELECT Provincia
                                    FROM Comune C 
                                    WHERE C.id = (SELECT Comune
                                                FROM Impresa
                                                WHERE Responsabile = :utente)) 
                        AND NOT EXISTS (SELECT *
                                        FROM Risposta T
                                        WHERE R.IDRichiesta = T.Richiesta AND T.Utente = :utente1)
                        AND StatoRichiesta = 'inviata' ";
    }
    else if($area == 'Comune') {
        $sql = "SELECT * 
                FROM Richiesta R                          
                WHERE R.Comune = (SELECT Comune
                                  FROM Comune C 
                                  WHERE C.id = (SELECT Comune
                                                FROM Impresa
                                                WHERE Responsabile = :utente)) 
                        AND NOT EXISTS (SELECT *
                                        FROM Risposta T
                                        WHERE R.IDRichiesta = T.Richiesta AND T.Utente = :utente1)
                        AND StatoRichiesta = 'inviata' ";
    }
    
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':utente', $user);
        $statement->bindValue(':utente1', $user);
        $statement->execute();
        $row = $statement->fetch();
        if($row) {
            do {
                 echo ' <div id="richiesta"> Richiesta di ' . $row['Utente'] . 
                      ' per il montaggio del suo mobile: '. $row['TipoMobile'] . 
                      '  </br>' . '<input type="button" id="btnrispondi" onclick="openFormRisp('
                      . $row['IDRichiesta'] .', \''. $user .'\' )"> Rispondi alla richiesta! </input> ' . 
                      '<input type="hidden" id= "iIDRichiesta" name= "iIDRichiesta'.$row['IDRichiesta'].'" value="'. $row['IDRichiesta'] . '" > </input>' . 
                      '<input type="hidden" id= "iIDUser" name= "iIDUser'.$user.'" value="'. $user . '" > </input>' .'</div>';
            } while( $row = $statement->fetch());
        }
        else {
            echo " </br> Nessuna richiesta nell'area selezionata! 
            </br> Potresti aver già risposto a tutte le richieste disponibili, </br> prova ad allargare l'area di ricerca o ricaricare la pagina per nuove richieste!";
        }
    }
}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore  </br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>