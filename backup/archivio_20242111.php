<?php

include '../php/connectDB.php';
include '../php/getuser.php';
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css">
        <link rel="stylesheet" type="text/css" href="../css/archivio.css"> 
    </head>
<body>
<header>
    <img class="imglogo" src="../img/logo.png" alt="Logo" > <span>&nbsp;</span>
    <span class="usrwelcome">Benvenuto utente: <?php echo $currentuser . ' [' . $usertype . ']' ?></span>
</header>
   <nav>
    <ul>
    <?php
        include '../php/header.php';
    ?>
  </ul>
  </nav>
  <hr>
 <div class="mainContent"> 
 <h1>Archivio</h1> 
 

    <?php 

    try{
          

        //se è un'impresa
        
        $sql = "SELECT Risp.IDRisposta, Risp.MessaggioRisposta, Risp.StatoRisposta, Risp.Utente AS UtenteRisposta,
                Ric.Utente AS UtenteRichiesta, Ric.IDRichiesta, Ric.TipoMobile, Ric.DataRichiesta, Ric.FasciaOraria, 
                C.Comune, C.Provincia, C.Regione, Ric.StatoRichiesta 
                FROM Risposta Risp INNER JOIN Richiesta Ric ON Ric.IDRichiesta = Risp.Richiesta
                                   INNER JOIN Comune C ON Ric.Comune = C.id
                WHERE Risp.Utente = :utente "; 
        if( $usertype == "cliente" ) //se è un cliente 
        {
            
            $sql = "SELECT Ric.IDRichiesta, Ric.TipoMobile, Ric.DataRichiesta, Ric.FasciaOraria, C.Comune, C.Provincia, C.Regione, Ric.StatoRichiesta 
                    FROM Richiesta Ric INNER JOIN Comune C ON Ric.Comune = C.id
                    WHERE Ric.Utente = :utente AND Ric.StatoRichiesta <> 'inviata' "; 
        }
                
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':utente', $currentuser);
        $statement->execute();
        if($usertype == "cliente") {
            $row = $statement->fetch();
            if($row) {
                do { 
                    echo   '<div class="card cardrichiesta">
                            <div class="container">
                            <h4><b>Richiesta</b> #'.$row['IDRichiesta'].'</h4> 
                            <p><strong>Tipo Mobile</strong>: '.$row['TipoMobile'].'</p> 
                            <p><strong>Data</strong>: '.$row['DataRichiesta'].'&nbsp;&nbsp;&nbsp;<strong>Fascia Oraria</strong>: '.$row['FasciaOraria'].'</p>
                            <p><strong>Localizzazione</strong>: '.$row['Comune'].'/'.$row['Provincia'].'/'.$row['Regione'].'</p>
                            <p><strong>Stato della richiesta</strong>: '.$row['StatoRichiesta'].'</p>
                            </div></div><div class="separatore">&nbsp;</div>';

                    if($row['StatoRichiesta'] == 'conclusa') {
                        //recupero le informazioni dell'impresa la cui risposta è stata accettata
                        $sql1 = "SELECT Ric.IDRichiesta, Risp.IDRisposta, I.Responsabile, I.Nome, Risp.MessaggioRisposta
                                FROM Richiesta Ric INNER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                                    INNER JOIN Impresa I ON I.Responsabile = Risp.Utente
                                WHERE Ric.IDRichiesta = :ric ";
                        $statement1 = $pdo->prepare($sql1);
                        $statement1->bindValue( ':ric', $row['IDRichiesta']);
                        $statement1->execute();
                        $row1 = $statement1->fetch();
                        
                        echo   '<div class="card">
                                <div class="container">
                                <p>La tua richiesta è stata portata a termine dal Titolare '.$row1['Responsabile'].'</p>
                                <p><strong>dell\'Impresa</strong>: '.$row1['Nome'].'</p> 
                                <p><strong>Messaggio dell\'impresa</strong>: '.$row1['MessaggioRisposta'].'</p>
                                </div></div><div class="separatore">&nbsp;</div>';

                        //controllo se è già stata effettuata una recensione a riguardo altrimenti rimando nella pagina per farla
                        $sql2 = "SELECT * FROM Recensione WHERE RichiestaRecensita = :ric AND Recensore = :user";
                        $statement2 = $pdo->prepare($sql2);
                        $statement2->bindValue( ':ric', $row['IDRichiesta']);
                        $statement2->bindValue( ':user', $currentuser);
                        $statement2->execute();
                        $row2 = $statement2->fetch(); 
                        if( $row2 == NULL) {
                            //non è ancora stata rencensita
                            echo '<div class="card">
                                <div class="container">
                                <p><strong>Come è stato il servizio offerto?</strong></p>
                                <p><a href="./nuovaRecensione.php?ric='.$row['IDRichiesta'].'" > clicca qui </a> per recensire ' . $row1['Nome'].'</p>
                                </div></div><div class="separatore">&nbsp;</div>';                        
                        }
                        else {
                            //è già stata recensita
                            //echo "Hai già recensito il servizio ricevuto per questa richiesta. Per vedere tutte le recensioni fatte vai nella tua Area Personale";
                            echo '<div class="card">
                                <div class="container">
                                <p><strong>Recensione?</strong></p>
                                <p>Hai già recensito il servizio ricevuto per questa richiesta. Per vedere tutte le recensioni fatte vai nella tua Area Personale.</p>
                                </div></div><div class="separatore">&nbsp;</div>'; 
                        }

                    }
                    else if($row['StatoRichiesta'] == 'scaduta') {
                        
                        echo '<div class="card">
                            <div class="container">
                            <p><strong>Stato della richiesta</strong></p>
                            <p>è passato l\'orario della tua richiesta senza nessuna risposta accettata.</p>
                            </div></div><div class="separatore">&nbsp;</div>'; 
                    }  
                    else if($row['StatoRichiesta'] == ' presa in carico') {
                        $sql1 = "SELECT Ric.IDRichiesta, Risp.IDRisposta, I.Responsabile, I.Nome, Risp.MessaggioRisposta
                                FROM Richiesta Ric INNER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                                    INNER JOIN Impresa I ON I.Responsabile = Risp.Utente
                                WHERE Ric.IDRichiesta = :ric ";
                        $statement1 = $pdo->prepare($sql1);
                        $statement1->bindValue( ':ric', $row['IDRichiesta']);
                        $statement1->execute();
                        $row1 = $statement1->fetch(); 
                        echo '<div class="card">
                            <div class="container">
                            <p>La tua richiesta è stata presa in carico dal Titolare: '.$row1['Responsabile'].'</p>
                            <p><strong>dell\'Impresa</strong>: '.$row1['Nome'].'</p> 
                            <p><strong>Azioni successive: </strong>Passato l\'orario prestabilito potrai recensire il servizio!</p>
                            </div></div><div class="separatore">&nbsp;</div>';
                    }
    
                    echo '<div class="separatore">&nbsp;</div><div id="divRisposte'.$row['IDRichiesta'].'"></div>';
                } while($row = $statement->fetch());
            }
        }
        else { //se è impresa
            $row = $statement->fetch();
            if($row) {
                do {
                    
                    echo '<div class="card cardrisposta">
                        <div class="container">
                        <h4><b>Richiesta</b> #'.$row['IDRichiesta'].'</h4> 
                        <p><strong>Tipo Mobile</strong>: '.$row['TipoMobile'].'</p> 
                        <p><strong>Data</strong>: '.$row['DataRichiesta'].'&nbsp;&nbsp;&nbsp;<strong>Fascia Oraria</strong>: '.$row['FasciaOraria'].'</p>
                        <p><strong>Localizzazione</strong>: '.$row['Comune'].'/'.$row['Provincia'].'/'.$row['Regione'].'</p>
                        <p><strong>Utente</strong>: '.$row['UtenteRichiesta'].'</p>
                        <p><strong>Stato della risposta</strong>: '.$row['StatoRisposta'].'</p>
                        </div></div><div class="separatore">&nbsp;</div>';


                    if( $row['StatoRisposta'] == 'accettata' ) {
                        if( $row['StatoRichiesta'] == 'conclusa' ) {
                                //controllo se è già stata effettuata una recensione a riguardo altrimenti rimando nella pagina per farla
                                $sql2 = "SELECT * FROM Recensione WHERE RichiestaRecensita = :ric AND Recensore = :user ";
                                $statement2 = $pdo->prepare($sql2);
                                $statement2->bindValue( ':ric', $row['IDRichiesta']);
                                $statement2->bindValue( ':user', $currentuser);
                                $statement2->execute();
                                $row2 = $statement2->fetch(); 
                                if( $row2 == NULL) {
                                    echo '<div class="card">
                                        <div class="container">
                                        <p> Come è stato il offrire il servizio al cliente? <a href="./nuovaRecensione.php?ric='.$row['IDRichiesta'].'" > clicca qui </a> per recensire ' . $row['UtenteRichiesta'].' </p>
                                        </div></div><div class="separatore">&nbsp;</div>';
                                }
                                else {
                                    //è già stato recensito
                                    echo '<div class="card">
                                        <div class="container">
                                        <p>Hai già recensito il cliente per questo servizio. Per vedere tutte le recensioni fatte vai nella tua Area Personale.</p>
                                        </div></div><div class="separatore">&nbsp;</div>'; 
                                }                        
                        }
                        else {
                                //richiesta a cui si riferisce non si è ancora conclusa
                            $sql2 = "SELECT A.FasciaOraria, A.DataRichiesta, A.Indirizzo,B.Email, (SELECT C.Comune FROM Comune C WHERE C.id = A.Comune) AS Comune
                                        FROM Richiesta A INNER JOIN Utente B ON A.Utente = B.UserID 
                                        WHERE A.IDRichiesta = :ric ";
                            $statement2 = $pdo->prepare($sql2);
                            $statement2->bindValue( ':ric', $row['IDRichiesta']);
                            $statement2->execute();
                            $row2 = $statement2->fetch(); 
                            echo '<div class="card">
                                <div class="container" style="border : solid 4px red; background-color:rgba(255,0,0,0.3)">
                                <p>Ricordati di presentarti il giorno '.$row2['DataRichiesta'].' alle '.$row2['FasciaOraria']. ' all\'indirizzo '.$row2['Indirizzo']. ' nel comune di '.$row2['Comune']. '</p>
                                <p>Puoi contattare il cliente via email all\'indirizzo '.$row2['Email']. ' </p>
                                </div></div><div class="separatore">&nbsp;</div>';

                            echo '<div class="card">
                                <div class="container">
                                <p>La tua risposta è stata accettata dal cliente, passato l\'orario prestabilito potrai recensire il cliente!</p>
                                </div></div><div class="separatore">&nbsp;</div>'; 
                        }
                    }
                    else if($row['StatoRisposta'] == 'inviata') {
                            echo '<div class="card">
                                <div class="container">
                                <p>La tua risposta non è stata ancora visionata dal cliente, verrai notificato quando questo accade.</p>
                                </div></div><div class="separatore">&nbsp;</div>';
                    }
                    else if($row['StatoRisposta'] == 'rifiutata') {

                            echo '<div class="card">
                                <div class="container">
                                <p>La tua risposta è stata rifiutata dal cliente. 
                                    Per cercare altre richieste a cui proporre il tuo servizio clicca <a href="./lookForRequest.php" >qui</a>!</p>
                                </div></div><div class="separatore">&nbsp;</div>';
                    }
                } while($row = $statement->fetch());
            }
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