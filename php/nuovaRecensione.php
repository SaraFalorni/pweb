<?php

include '../php/connectDB.php';
include '../php/getuser.php';
                ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <link rel="stylesheet" type="text/css" href="../css/LookForRisposte.css">
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
    <h1>Nuova Recensione</h1> <br/>
    <div class="card">
        <div id="dRecensione" class="container">
            <form action="./insertRecensione.php" method="POST" id="formRecensione">
                    <?php

                        $connection = new connectDB();
                        $pdo = $connection->getPDO();
                    
                        try{

                            $richiesta = $_GET['ric'];
                            $sql = "SELECT Ric.IDRichiesta, Ric.Utente AS UtenteRichiesta, Risp.Utente AS UtenteRisposta, Ric.TipoMobile, Ric.DataRichiesta, Ric.FasciaOraria, C.Comune
                                    FROM Richiesta Ric LEFT OUTER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                        LEFT OUTER JOIN Comune C ON Ric.Comune = C.id
                                    WHERE Ric.IDRichiesta = :richiesta";
                            $statement = $pdo->prepare($sql);
                            $statement->bindValue(':richiesta', $richiesta);
                            $statement->execute();
                            $row = $statement->fetch(); 
                            if($row){

                                echo '<input type="hidden" id="idRichiesta" name="idRichiesta" value="'.$row['IDRichiesta'].'"> </input> ';
                                
                            if( $usertype == 'cliente') {
                                echo "<p>Scrivi una recensione sul servizio offerto da <b>". $row['UtenteRisposta'] ."</b> per il tuo mobile ". $row['TipoMobile'].'</p>';
                                echo '<input type="hidden" id="recensore" name="recensore" value="'. $row['UtenteRichiesta'] .'"> </input> ';
                                echo '<input type="hidden" id="recensito" name="recensito" value="'. $row['UtenteRisposta'] .'"> </input> ';
                            }
                            else { //impresa
                                echo "<p>Scrivi una recensione sull cliente <b>". $row['UtenteRichiesta'] ."</b> a cui hai offerto da il servizio di montaggio per il mobile ".$row['TipoMobile'].'</p>';
                                echo '<input type="hidden" id="recensore" name="recensore" value="'. $row['UtenteRisposta'] .'"> </input> ';
                                echo '<input type="hidden" id="recensito" name="recensito" value="'. $row['UtenteRichiesta'] .'"> </input> ';
                            }
                            }
                            
                        }

                        catch(PDOException | Exception $e) {
                            $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nel caricare la pagina per la recensione </br>";
                            echo $erroreinserimento ;
                            echo $emess;
                        }
                    
                        $connection->close();
                        $pdo = null;

                    ?>
                    
            <div id ="divRating"> <p> <b> Rating </b> </p>           
                <label for="1star"> 1 </label>
                <input type="radio" id="1star" name="rating" value="1" > <br/>
                <label for="2star"> 2 </label>
                <input type="radio" id="2star" name="rating" value="2" > <br/>
                <label for="3star"> 3 </label>
                <input type="radio" id="3star" name="rating" value="3" > <br/>
                <label for="4star"> 4 </label>
                <input type="radio" id="4star" name="rating" value="4" > <br/>
                <label for="5star"> 5 </label>
                <input type="radio" id="5star" name="rating" value="5" > <br/>
                
            </div> </br>

            <p> <b> Scrivi qualcosa in più! </b> </p>  
            <textarea type="textarea"  id="textRec" name="textRec"></textarea> <br/>
        <br/> <br/> <input class="btn" type="submit" value="Submit"> </form>
                

        </div>
    </div>            
</div>
</body>
</html>