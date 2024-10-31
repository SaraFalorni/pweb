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
                    <li><a href="../index.html" id="logoutButton"> Logout </a></li>';
                }
                else if($usertype == 'impresa') {
                    //index impresa
                    echo '<li><a href="./indexImpresa.php">Home</a></li>
                    <li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
                    <li><a href="./areaPersonale.php">Archivio Risposte</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="../index.html" id="logoutButton"> Logout </a></li>';
                }
            
        ?>
  </ul>
  </nav>
  <hr>
  <h1>Nuova Recensione</h1> <br/>
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

                        $richiesta = $_GET['ric'];
                        $sql = "SELECT Ric.IDRichiesta, Ric.Utente AS UtenteRichiesta, Risp.Utente AS UtenteRisposta, Ric.TipoMobile, Ric.DataRichiesta, Ric.FasciaOraria, C.Comune
                                FROM Richiesta Ric LEFT OUTER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                     LEFT OUTER JOIN Comune C ON Ric.Comune = C.id
                                WHERE Ric.IDRichiesta = :richiesta";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':richiesta', $richiesta);
                        $statement->execute();
                        $row = $statement->fetch(); //solo un record perchè la condizione è sulla chiave primaria
                        echo '<div id="dRecensione">';
                        echo '<form action="./insertRecensione.php" method="POST" id="formRecensione">';
                        echo '<input type="hidden" id="idRichiesta" name="idRichiesta" value="'.$row['IDRichiesta'].'"> </input> ';
                        
                        if( $usertype == 'cliente') {
                            echo "Scrivi una recensione sul servizio offerto da ". $row['UtenteRisposta'] ." per il tuo mobile ". $row['TipoMobile'];
                            echo '<input type="hidden" id="recensore" name="recensore" value="'. $row['UtenteRichiesta'] .'"> </input> ';
                            echo '<input type="hidden" id="recensito" name="recensito" value="'. $row['UtenteRisposta'] .'"> </input> ';
                        }
                        else { //impresa
                            echo "Scrivi una recensione sull cliente ". $row['UtenteRichiesta'] ."a cui hai offerto da il servizio di montaggio per il mobile ".$row['TipoMobile'];
                            echo '<input type="hidden" id="recensore" name="recensore" value="'. $row['UtenteRisposta'] .'"> </input> ';
                            echo '<input type="hidden" id="recensito" name="recensito" value="'. $row['UtenteRichiesta'] .'"> </input> ';
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
                
                    <div id ="divRating"> Rating <br/>           
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

                    Scrivi qualcosa in più! <br/> <textarea type="textarea"  id="textRec" name="textRec"></textarea> <br/>
                <br/> <br/> <input class="btn" type="submit" value="Submit"> </form>
                       


        </div>            
  </div>
</body>
</html>