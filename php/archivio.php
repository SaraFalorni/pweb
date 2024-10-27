<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
<body>
    <header>
    
    <img src="logo.png" alt="Logo"> <span>&nbsp;My website</span>

  </header>
   <nav>
    <ul>
        <!-- index in base al tipo di utente -->
        <?php
                if(!isset($_COOKIE["usertype"])) {
                    echo "cookie non settato </br>" ;
                }
                else {
                $usertype = $_COOKIE["usertype"]; }  

                if($usertype == 'cliente') {
                    //index cliente
                    echo '<li><a href="./indexCliente.php">Home</a></li>
                    <li><a href="./LookForRisposte.php">Risposte ricevute</a></li>
                    <li><a href="./archivio.php">Archivio Richieste</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
                    <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>';
                }
                else if($usertype == 'impresa') {
                    //index impresa
                    echo '<li><a href="./indexImpresa.php">Home</a></li>
                    <li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
                    <li><a href="./areaPersonale.php">Archivio Risposte</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>';
                }
            
        ?>
  </ul>
  </nav>
  <hr>
  <h1>Archivio</h1> <br/>
  <div class="mainContent"> 


    <?php 

    include '../php/connectDB.php';

    $connection = new connectDB();
    $pdo = $connection->getPDO();

    try{
        if(!isset($_COOKIE["usertype"])) {
            echo "cookie usertype non settato </br>" ;
        }
        else {
            $usertype = $_COOKIE["usertype"]; }   
        
        if(!isset($_COOKIE["user"])) {
            echo "cookie user non settato </br>" ;
        }
        else {
            $user = $_COOKIE["user"]; }   

        //se è un'impresa
        
        $sql = "SELECT Risp.IDRisposta, Risp.MessaggioRisposta, Risp.StatoRisposta, Risp.Utente AS UtenteRisposta,Ric.Utente AS UtenteRichiesta, Ric.IDRichiesta, Ric.TipoMobile, Ric.DataRichiesta, Ric.FasciaOraria, C.Comune, Ric.StatoRichiesta 
                FROM Risposta Risp INNER JOIN Richiesta Ric ON Ric.IDRichiesta = Risp.Richiesta
                                   INNER JOIN Comune C ON Ric.Comune = C.id
                WHERE Risp.Utente = :utente "; //order by timestamp??? così sono in ordine cronologico
        if( $usertype == "cliente" ) //se è un cliente 
        {
            //Troppe info troppe tabelle, o riguardare struttara db aggiungendo ridondanze utili oppure capire come fare join non troppo pesanti
            $sql = "SELECT Ric.IDRichiesta, Ric.TipoMobile, Ric.DataRichiesta, Ric.FasciaOraria, C.Comune, Ric.StatoRichiesta 
                    FROM Richiesta Ric INNER JOIN Comune C ON Ric.Comune = C.id
                    WHERE Ric.Utente = :utente AND Ric.StatoRichiesta <> 'inviata'
                    ORDER BY TimeStampRichiesta "; //order by timestamp??? così sono in ordine cronologico
        }
                
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':utente', $user);
        $statement->execute();
        if($usertype == "cliente") {
            $row = $statement->fetch();
            do {
                echo ' <div > <li id= "'. $row['IDRichiesta'] .'" name="rRichieste">
                 Richiesta per il montaggio di ' .$row['TipoMobile']. ' nella data '.$row['DataRichiesta'].
               ' nella fascia oraria ' .$row['FasciaOraria']. ' nel comune di ' .$row['Comune']. ' 
               </br> Stato della richiesta : '. $row['StatoRichiesta'] . '</br>';
                
                if($row['StatoRichiesta'] == 'conclusa') {
                    //recupero le informazioni dell'impresa la cui risposta è stata accettata
                    $sql1 = "SELECT Ric.IDRichiesta, Risp.IDRisposta, I.Responsabile, I.Nome, Risp.MessaggioRisposta
                             FROM Richiesta Ric INNER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                                INNER JOIN Impresa I ON I.Responsabile = Risp.Utente
                             WHERE Ric.IDRichiesta = :ric ";
                    $statement1 = $pdo->prepare($sql1);
                    $statement1->bindValue( ':ric', $row['IDRichiesta']);
                    $statement1->execute();
                    $row1 = $statement1->fetch(); //se c'è è 1 sicuramente
                    echo 'La tua richiesta è stata portata a termine dall\'utente '.$row1['Responsabile'].
                     ' titolare dell\'impresa '. $row1['Nome'] . ' che ha risposto alla tua richiesta con il messaggio " '
                     . $row1['MessaggioRisposta'] .' " </br>' ;
                    //controllo se è già stata effettuata una recensione a riguardo altrimenti rimando nella pagina per farla
                    $sql2 = "SELECT * FROM Recensione WHERE RichiestaRecensita = :ric AND Recensore = :user";
                    $statement2 = $pdo->prepare($sql2);
                    $statement2->bindValue( ':ric', $row['IDRichiesta']);
                    $statement2->bindValue( ':user', $user);
                    $statement2->execute();
                    $row2 = $statement2->fetch(); //se c'è è 1 sicuramente
                    if( $row2 == NULL) {
                        //non è ancora stata rencensita
                        echo " Com'è stato il servizio offerto? clicca <a href='./nuovaRecensione.php?ric=".$row['IDRichiesta']."' > qui </a> per recensire " . $row1['Nome'] ;
                    }
                    else {
                        //è già stata recensita
                        echo "Hai già recensito il servizio ricevuto per questa richiesta. Per vedere tutte le recensioni fatte vai nella tua Area Personale";
                    }

                }
                else if($row['StatoRichiesta'] == 'scaduta') {
                    echo "è passato l'orario della tua richiesta senza nessuna risposta accettata.";

                }  
                else if($row['StatoRichiesta'] == ' presa in carico') {
                    $sql1 = "SELECT Ric.IDRichiesta, Risp.IDRisposta, I.Responsabile, I.Nome, Risp.MessaggioRisposta
                             FROM Richiesta Ric INNER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                                INNER JOIN Impresa I ON I.Responsabile = Risp.Utente
                             WHERE Ric.IDRichiesta = :ric ";
                    $statement1 = $pdo->prepare($sql1);
                    $statement1->bindValue( ':ric', $row['IDRichiesta']);
                    $statement1->execute();
                    $row1 = $statement1->fetch(); //se c'è è 1 sicuramente
                    echo "la tua risposta è stata presa in carico da ". $row1['Responsabile']." dell'impresa ".$row1['Nome'].", passato l'orario prestabilito potrai recensire il servizio!";
                }

               echo ' </br> </br> <div id="divRisposte'.$row['IDRichiesta'].'"></div>
               </div>  </li> '  ;
            } while($row = $statement->fetch());
        }
        else { //se è impresa
            $row = $statement->fetch();
            do {
                echo ' <div > <li id= "'. $row['IDRisposta'] .'" name="rRisposta">
                 Risposta per la richiesta per il montaggio di ' .$row['TipoMobile']. ' nella data '.$row['DataRichiesta'].
               ' nella fascia oraria ' .$row['FasciaOraria']. ' nel comune di ' .$row['Comune']. ' dell\'utente '. $row['UtenteRichiesta'] 
               .' : " '.$row['MessaggioRisposta'] .' " 
               </br> Stato della risposta : '. $row['StatoRisposta'] . '</br>';

               if( $row['StatoRisposta'] == 'accettata' ) {
                   if( $row['StatoRichiesta'] == 'conclusa' ) {
                        //controllo se è già stata effettuata una recensione a riguardo altrimenti rimando nella pagina per farla
                        $sql2 = "SELECT * FROM Recensione WHERE RichiestaRecensita = :ric AND Recensore = :user ";
                        $statement2 = $pdo->prepare($sql2);
                        $statement2->bindValue( ':ric', $row['IDRichiesta']);
                        $statement2->bindValue( ':user', $user);
                        $statement2->execute();
                        $row2 = $statement2->fetch(); //se c'è è 1 sicuramente
                        if( $row2 == NULL) {
                            //non è ancora stato recensito
                            echo " Com'è stato il offrire il servizio al cliente? clicca <a href='./nuovaRecensione.php?ric=".$row['IDRichiesta']."' > qui </a> per recensire " . $row['UtenteRichiesta'] ;
                        }
                        else {
                            //è già stato recensito
                            echo "Hai già recensito il cliente per questo servizio. Per vedere tutte le recensioni fatte vai nella tua Area Personale";
                        }                        
                   }
                   else {
                        //richiesta a cui si riferisce non si è ancora conclusa
                        echo "La tua risposta è stata accettata dal cliente, passato l'orario prestabilito potrai recensire il cliente! ";
                   }
               }
               else if($row['StatoRisposta'] == 'inviata') {
                    echo "La tua risposta non è stata ancora visionata dal cliente, verrai notificato quando questo accade.";
               }
               else if($row['StatoRisposta'] == 'rifiutata') {
                    echo 'La tua risposta è stata rifiutata dal cliente. Per cercare altre richieste a cui proporre il tuo servizio clicca <a href="./lookForRequest.php" >qui</a>!';
               }
            } while($row = $statement->fetch());
        }
    }  

    catch(PDOException | Exception $e) {
        $emess = $e->getMessage();
        $erroreinserimento = "C'è stato un errore nell'accedere all'archivio </br>";
        echo $erroreinserimento ;
        echo $emess;
    }

    $connection->close();
    $pdo = null;

    ?>


</div>
</body>
</html>