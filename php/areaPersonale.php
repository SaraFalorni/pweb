<?php

include '../php/connectDB.php';
include '../php/getuser.php';
                ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <script type="text/javascript" src="../js/areaPersonale.js"></script>
        <script type="text/javascript" src="../js/signUp.js"></script> <!--per generare le opzioni di provincia e comune-->
    </head>
<body>
<header>
    <img class="imglogo" src="../img/logo.png" alt="Logo" > <span>&nbsp;</span>
    <span class="usrwelcome">Benvenuto utente: <?php echo $currentuser . ' [' . $usertype . ']' ?></span>  
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
  <div class="mainContent"> 
  <h2>Area Personale</h2> <br/>

  <li> <input type="button" onclick = 'openModificaProfilo()' value="Modifica Profilo">  </input> </li>
            <div id="dModificaProfilo" name="dModificaProfilo" style="display:none"> 
                    <h3>Modifica le tue informazioni personali </h3>
                    <p>* UserId non è modificabile </br> Inserisci la password nel campo 'Conferma password' per poter effettuare le modifiche!</p>
                    <?php
                        /* include '../php/connectDB.php';

                        $connection = new connectDB();
                        $pdo = $connection->getPDO();  */
                         try{
                             if(!isset($_COOKIE["user"])) {
                                 echo "cookie non settato </br>" ;
                             }
                             else {
                             $user = $_COOKIE["user"]; }  
             
                             $sql = "SELECT U.UserID, U.Nome, U.Cognome,U.Email, U.Pwd, U.DataNascita, U.Indirizzo, U.FotoUser, D.IDImpresa, D.NomeImpresa, 
                                            D.Descrizione, D.Comune, D.Responsabile,D.NomeComune, D.Provincia, D.Regione
                                      FROM Utente U LEFT OUTER JOIN (SELECT  I.IDImpresa, I.Nome AS NomeImpresa, I.Descrizione, I.Comune, I.Responsabile,C.Comune AS NomeComune, C.Provincia, C.Regione
                                                                     FROM Impresa I INNER JOIN Comune C ON I.Comune = C.id) AS D ON U.UserID = D.Responsabile
                                      WHERE UserID = :user ";
                             $statement = $pdo->prepare($sql);
                             $statement->bindValue( ':user', $user);
                             $statement->execute();
                             $row = $statement->fetch();

                             echo 'Le tue informazioni personali: ';

                             echo '<form action="./modificaProfilo.php" method="POST"> 
                             UserID <br/> <input type="text" class="UserInput" name="UserID" value="'.$row['UserID'].'" readonly> <br/>
                             Nome <br/> <input type="text" class="UserInput" name="nome" value="'.$row['Nome'].'"> <br/>
                             Cognome <br/> <input type="text" class="UserInput" name="cognome" value="'.$row['Cognome'].'"> <br/>
                             email <br/> <input type="text" class="UserInput" name="email" value="'.$row['Email'].'"> <br/>
                             password <br/> <input type="password" class="UserInput" id="pwd" name="pwd" value="'.$row['Pwd'].'"> <br/>
                             conferma password <br/> <input type="password" class="UserInput" id="cpwd" name="cpwd" onblur="checkPasswordString()" > 
                             <label id="errorPwd" style="display:none;">le password inserite differiscono</label> <br/>
                             Data Di Nascita <br/> <input type="date" class="UserInput" id="birthDate" name="birthDate" onblur="checkBirthDate()" value="'.$row['DataNascita'].'"> 
                             <label id="errorBirthDate" style="display:none;">Data di Nascita non accettabile</label> <br/>
                             Indirizzo <br/> <input type="text" class="UserInput" name="indirizzo" value="'.$row['Indirizzo'].'"> <br/>
                             Foto <br/> <input type="file" class="btn-secondario" id="UserPicture" name="foto" accept="image/png, image/jpeg" /> <br/>';
                            
                             if( !empty($row['IDImpresa']) ) //se l'utente è impresa mostra anche le informazioni relative all'impresa
                           { echo  '<div name="Impresa" id="Impresa" >
                                Nome dell\'Impresa <br/> <input type="text" class="UserInput" name = "nomeImpresa" value="'.$row['NomeImpresa'].'"> <br/>
                                <label for="Regione">Regione</label>
                                    <select name="Regione" id="Regione" onclick = "emptyOptionsProvincia();">
                                        <option disabled selected value> '.$row['Regione'].' </option>
                                        <option value="Abruzzo">Abruzzo</option>
                                        <option value="Basilicata">Basilicata</option>
                                        <option value="Calabria">Calabria</option>
                                        <option value="Campania">Campania</option>
                                        <option value="Emilia Romagna">Emilia Romagna</option>
                                        <option value="Friuli Venezia Giulia">Friuli Venezia Giulia</option>
                                        <option value="Lazio">Lazio</option>
                                        <option value="Liguria">Liguria</option>
                                        <option value="Lombardia">Lombardia</option>
                                        <option value="Marche">Marche</option>
                                        <option value="Molise">Molise</option>
                                        <option value="Piemonte">Piemonte</option>
                                        <option value="Puglia">Puglia</option>
                                        <option value="Sardegna">Sardegna</option>
                                        <option value="Sicilia">Sicilia</option>
                                        <option value="Toscana">Toscana</option>
                                        <option value="Trentino Alto Adige">Trentino Alto Adige</option>
                                        <option value="Umbria">Umbria</option>
                                        <option value="Val D’Aosta">Val D’Aosta</option>
                                        <option value="Veneto">Veneto</option>
                                    </select> &nbsp; &nbsp; 
                                    <label for="Provincia">Provincia</label>
                                    <select name="Provincia" id="Provincia" onfocus="findProvincia(\'Regione\');" >
                                        <option disabled selected value> '.$row['Provincia'].' </option>                    
                                    </select> <br/> <br/>
                                    <label for="Comune">Comune</label>
                                    <select name="Comune" id="Comune" onfocus="LoadComuni(\'Comune\');" >
                                         <option disabled selected value> '.$row['NomeComune'].' </option>
                                    </select> <br/>
                                  <!-- Comune <br/> <input type="text" name="Comune" class="UserInput"> <br/> -->
                                    Descrizione <br/> <textarea type="textarea" class="UserInput" id="bio" name="bio"></textarea> <br/>
                              </div> <br/> <br/>'; }

                                 echo   '<input type="submit" value="submit"></form>';
                         }  
             
                         catch(PDOException | Exception $e) {
                             $emess = $e->getMessage();
                              $erroreinserimento = "C'è stato un errore nell'accedere alle informazioni personali </br>";
                              echo $emess;
                          }
                         
                         $connection->close();
                         $pdo = null;

                    ?>
                </br>  
                <input type="button" onclick='closeModificaProfilo()' value="Chiudi"> </input>
            </div>
             
           <div id="divRecensionifatte" >
               <h3>Recensioni scritte</h3>
               <?php
                    //include '../php/connectDB.php';

                    $connection = new connectDB();
                    $pdo = $connection->getPDO();
                     try{
                         if(!isset($_COOKIE["user"])) {
                             echo "cookie non settato </br>" ;
                         }
                         else {
                         $user = $_COOKIE["user"]; }  

                         if(!isset($_COOKIE["usertype"])) {
                            echo "cookie non settato </br>" ;
                        }
                        else {
                        $usertype = $_COOKIE["usertype"]; }  

                         $sql = "SELECT Rec.IDRecensione, Rec.Recensito, Rec.Recensore, Rec.TestoRecensione, Rec.Rating, Ric.TipoMobile, Ris.MessaggioRisposta
                                 FROM Recensione Rec LEFT OUTER JOIN Richiesta Ric ON Rec.RichiestaRecensita = Ric.IDRichiesta
                                        LEFT OUTER JOIN Risposta Ris ON Ric.RispostaAccettata = Ris.IDRisposta
                                 WHERE Rec.Recensore = :user";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue( ':user', $user);
                        $statement->execute();
                        $row = $statement->fetch();
                        if( $row != NULL )
                        {    if($usertype == 'cliente') {
                                do {
                                    echo '<div id="recensione'.$row['IDRecensione'].'">';
                                    echo 'Recensione del servizio offerto da '.$row['Recensito'].' per il montaggio del mobile '.$row['TipoMobile'].': </br>';
                                    echo 'Rating: '.$row['Rating'].' stelle </br>';
                                    echo '</br> "'. $row['TestoRecensione'].' "</br>' ;
                                    echo '</div>';
                                } while( $row = $statement->fetch() );
                            }
                            else {//user è impresa
                                do {
                                    echo '<div id="recensione'.$row['IDRecensione'].'">';
                                    echo 'Recensione del cliente '.$row['Recensito'].' in riferimento al montaggio del mobile '.$row['TipoMobile'].': </br>';
                                    echo 'Rating: '.$row['Rating'].' stelle </br>';
                                    echo '</br> "'. $row['TestoRecensione'].' "</br>' ;
                                    echo '</div>';
                                } while( $row = $statement->fetch() );
                            }
                            
                        }  
                        else {
                            echo "Non hai ancora scritto nessuna recensione.";
                        }
                    }
             
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle recensioni fatte </br>";
                            echo $emess;
                        }
                    
                    $connection->close();
                    $pdo = null;
               ?>
               
           </div>  

           <div id="divRecensionifatte" >
               <h3>Recensioni ricevute</h3>
               <?php
                    //include '../php/connectDB.php';

                    $connection = new connectDB();
                    $pdo = $connection->getPDO();
                    try{
                        if(!isset($_COOKIE["user"])) {
                            echo "cookie non settato </br>" ;
                        }
                        else {
                        $user = $_COOKIE["user"]; }  

                        if(!isset($_COOKIE["usertype"])) {
                            echo "cookie non settato </br>" ;
                        }
                        else {
                        $usertype = $_COOKIE["usertype"]; }  

                        $sql = "SELECT Rec.IDRecensione, Rec.Recensito, Rec.Recensore, Rec.TestoRecensione, Rec.Rating, Ric.TipoMobile, Ris.MessaggioRisposta
                                FROM Recensione Rec LEFT OUTER JOIN Richiesta Ric ON Rec.RichiestaRecensita = Ric.IDRichiesta
                                        LEFT OUTER JOIN Risposta Ris ON Ric.RispostaAccettata = Ris.IDRisposta
                                WHERE Rec.Recensito = :user";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue( ':user', $user);
                        $statement->execute();
                        $row = $statement->fetch();
                        if( $row != NULL) {
                            if($usertype == 'cliente') {
                                do {
                                    echo '<div id="recensione'.$row['IDRecensione'].'">';
                                    echo 'Recensione dell\'impresa '.$row['Recensore'].' per il montaggio del mobile '.$row['TipoMobile'].': </br>';
                                    echo 'Rating: '.$row['Rating'].' stelle </br>';
                                    echo '</br> "'. $row['TestoRecensione'].' "</br>' ;
                                    echo '</div>';
                                } while( $row = $statement->fetch() );
                            }
                            else {//user è impresa
                                do {
                                    echo '<div id="recensione'.$row['IDRecensione'].'">';
                                    echo 'Recensione del tuo servizio per '.$row['Recensore'].' per il montaggio del mobile '.$row['TipoMobile'].': </br>';
                                    echo 'Rating: '.$row['Rating'].' stelle </br>';
                                    echo '</br> "'. $row['TestoRecensione'].' "</br>' ;
                                    echo '</div>';
                                } while( $row = $statement->fetch() );
                            }
                        } 
                        else {
                            echo "Non hai ancora ricevuto nessuna recensione.";
                        }                      
                    }  
            
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle recensioni fatte </br>";
                            echo $emess;
                        }
                    
                    $connection->close();
                    $pdo = null;
               ?>
           </div>  

  </div>
	

</body>
</html>