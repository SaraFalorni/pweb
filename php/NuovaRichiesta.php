<?php

include '../php/connectDB.php';
include '../php/getuser.php';
                ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <link rel="stylesheet" type="text/css" href="../css/nuovaRichiesta.css"> 
        <script type="text/javascript" src="../js/location.js"></script> 
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
  <div class="ricmainContent">
    <h2>Inserisci una nuova Richiesta!</h2> 
    <p id="descnuovaric">Riempi questo semplice modulo con le informazioni necessarie per garantire il miglior servizio possibile! </br>
        Una volta cliccato 'Invia' la tua richiesta sarà visibile alle imprese che, 
        se disponibili, risponderanno confermando la loro accettazione della richiesta. </p>
    <form action="./newRichiesta.php" method="POST" id="fnewRic" name="fnewRic"> 
        Tipo di mobile </br> <div class="sottotitolo"> Cerca di essere più specifico possibile in modo semplice! </div><input type="text" id="iTipoMobile" name="iTipoMobile" class="infoMobile"> </input> </br>
        Data </br> <input type="date" id= "iDataRic" name= "iDataRic" class="infoMobile">  </input> </br> 
        Fascia Oraria </br> 
        <select id="selFasciaOraria" name="selFasciaOraria" class="infoMobile"> 
            <option disabled selected value> -- Scegli la fascia oraria -- </option>
            <option value="8:00 - 10:00"> 8:00 - 10:00 </option>
            <option value="10:00 - 12:00"> 10:00 - 12:00 </option>
            <option value="12:00 - 14:00"> 12:00 - 14:00 </option>
            <option value="14:00 - 16:00"> 14:00 - 16:00 </option>
            <option value="16:00 - 18:00"> 16:00 - 18:00 </option>
            <option value="18:00 - 20:00"> 18:00 - 10:00 </option>
        </select> </br>
       
            <?php 
                
                
                try{
                
                    echo 'Regione </br> <select id="selRegione" name="selRegione" class="infoMobile"> <option disabled selected value> -- Scegli la regione -- </option>' ;
                    $sql = "SELECT Regione FROM Comune GROUP BY Regione ORDER BY Regione";
                    $statement = $pdo->prepare($sql);
                    $statement->execute();
                    $row = $statement->fetch();
                    if($row) {
                        do {
                             echo '<option value=' . $row['Regione'] . '>' . $row['Regione']. '</option>' ;
                        } while( $row = $statement->fetch());
                    }
                    echo ' </select> </br> ';

                }
                
                catch(PDOException | Exception $e) {
                    $emess = $e->getMessage();
                     $erroreinserimento = "C'è stato un errore nell'ottenere la provincia </br>";
                     echo $erroreinserimento ;
                     echo $emess;
                 }
                
                $connection->close();
                $pdo = null;
            ?>
         Provincia </br>  <!-- *** -->
                <select name="Provincia" id="Provincia" onfocus="findProvincia('selRegione');" >
                    <option disabled selected value> -- Scegli la provincia -- </option>                    
                </select>
                </br>
        Comune </br> <select id="selComune" name="selComune" class="infoMobile" onfocus="LoadComuni('selComune');" >
            <option disabled selected value> -- Scegli il comune -- </option>
        </select> </br>
        Indirizzo <br/> <input type="text" class="UserInput testoesteso" name="indirizzo"> <br/>   
        Hai qualcosa da aggiungere? Scrivi qui! </br> <textarea type="textarea" id="iMessRichiesta" name="iMessRichiesta" class="infoMobile"> </textarea> </br>
        Foto del mobile ? (montato, smontato) </br> 
        <input type="file" class="btn-secondario" id="iFotoMobile" name="iFotoMobile" class="infoMobile"> </input> </br>
        <br/>
        Link al prodotto sul sito del venditore (opzionale)</br> <div class="sottotitolo" > Copia e incolla l'indirizzo web completo del modello di mobile. </br> Questo aiuterà l'azienda a comprendere al meglio le caratteristiche del mobile. 
        </br></div><input type="text" id="iLinkMobile" name="iLinkMobile" class="infoMobile"> </input>
        </br>

        <input class="btn btn-secondario" type="submit" id="subNewRic" value="Invia richiesta!"> </input>
    </form>
  </div>
</body>
</html>