<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <link rel="stylesheet" type="text/css" href="../css/nuovaRichiesta.css"> 
        <script type="text/javascript" src="../js/nuovaRichiesta.js"></script> 
    </head>
<body>
    <header>
    
    <img src="logo.png" alt="Logo"> <span>&nbsp;My website</span>

   </header>
   <nav>
    <ul>
      <li><a href="./indexCliente.php">Home</a></li>
      <li><a href="./lookForRisposte.php" id="lookForRisp" >Risposte ricevute</a></li>
      <li><a href="./archivio.php">Archivio Richieste</a></li>
      <li><a href="./areaPersonale.php">Area Personale</a></li>
      <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
      <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>
    </ul>
  </nav>
  <hr>
    <h1>Scrivi una nuova Richiesta!</h1> 
    <h3>Riempi questo semplice modulo con le informazioni necessarie per garantire il miglior servizio possibile! </br>
        Una volta cliccato 'submit' la tua richiesta sarà visibile alle imprese, che se disponibili risponderanno confermando la loro disponibilità. </h3>
    <form action="./newRichiesta.php" method="POST" id="fnewRic" name="fnewRic"> 
        Tipo di mobile </br> <div class="sottotitolo"> Cerca di essere più specifico possibile in modo semplice! </div><input type="text" id="iTipoMobile" name="iTipoMobile" class="infoMobile"> </input> </br>
        Data </br> <input type="date" id= "iDataRic" name= "iDataRic" class="infoMobile">  </input> </br> 
        Fascia Oraria </br> <select id="selFasciaOraria" name="selFasciaOraria" class="infoMobile"> 
            <option disabled selected value> -- Scegli la fascia oraria -- </option>
            <option value="8:00 - 10:00"> 8:00 - 10:00 </option>
            <option value="10:00 - 12:00"> 10:00 - 12:00 </option>
            <option value="12:00 - 14:00"> 12:00 - 14:00 </option>
            <option value="14:00 - 16:00"> 14:00 - 16:00 </option>
            <option value="16:00 - 18:00"> 16:00 - 18:00 </option>
            <option value="18:00 - 20:00"> 18:00 - 10:00 </option>
        </select> </br>

            <?php 
                include '../php/connectDB.php';
                
                $connection = new connectDB();
                $pdo = $connection->getPDO();
                
                try{
                
                    echo 'Regione </br> <select id="selRegione" name="selRegione" class="infoMobile"> <option disabled selected value> -- Scegli la regione -- </option>' ;
                    $sql = "SELECT Regione FROM Comune";
                    $statement = $pdo->prepare($sql);
                    $statement->execute();
                    $row = $statement->fetch();
                    if($row) {
                        do {
                             echo '<option value=' . $row['Regione'] . '>' . $row['Regione']. '</option>' ;
                        } while( $row = $statement->fetch());
                    }
                    echo ' </select> </br> ';

                        echo 'Provincia </br> <select id="selProvincia" name="selProvincia" class="infoMobile"> <option disabled selected value> -- Scegli la provincia -- </option>' ;
                        $sql = "SELECT Provincia FROM Comune";
                        $statement = $pdo->prepare($sql);
                        $statement->execute();
                        $row = $statement->fetch();
                        if($row) {
                            do {
                                 echo '<option value=' . $row['Provincia'] . '>' . $row['Provincia']. '</option>' ;
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
        
        Comune </br> <select id="selComune" name="selComune" class="infoMobile" onfocus="LoadComuni();" >
            <option disabled selected value> -- Scegli il comune -- </option>
        </select> </br>
        Hai qualcosa da aggiungere? Scrivi qui! </br> <textarea type="textarea" id="iMessRichiesta" name="iMessRichiesta" class="infoMobile"> </textarea> </br>
        Foto del mobile ? (montato, smontato) </br> <input type="file" id="iFotoMobile" name="iFotoMobile" class="infoMobile"> </input> </br>
        Link al prodotto sul sito del venditore (opzionale)</br> <div class="sottotitolo" > Copia e incolla l'indirizzo web completo del modello di mobile. </br> Questo aiuterà l'azienda a comprendere al meglio le caratteristiche del mobile. 
        </br></div><input type="text" id="iLinkMobile" name="iLinkMobile" class="infoMobile"> </input>
        </br>

        <input type="submit" id="subNewRic"> </input>
    </form>

</body>
</html>