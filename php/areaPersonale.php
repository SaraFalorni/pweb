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

                if($usertype == 'cliente') {
                    //index cliente
                    echo '<li><a href="./LookForRisposte.php">Risposte ricevute</a></li>
                        <li><a href="./archivio.php">Archivio Richieste</a></li>
                        <li><a href="./areaPersonale.php">Area Personale</a></li>
                        <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
                        <li><a href="../index.html" id="logoutButton"> Logout </a></li>';
                }
                else if($usertype == 'impresa') {
                    //index impresa
                    echo '<li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
                        <li><a href="./archivio.php">Archivio Risposte</a></li>
                        <li><a href="./areaPersonale.php">Area Personale</a></li>
                        <li><a href="../index.html" id="logoutButton"> Logout </a></li>';
                }
                
        ?>
    </ul>
  </nav>
  <hr>
  <div class="mainContent"> 
  <h2>Area Personale</h2> <br/>

  

   <p> <input type="button" class="btn" onclick = 'openModificaProfilo()' value="Modifica Profilo">  </input> </p>
            <div id="dModificaProfilo" name="dModificaProfilo" style="display:none"> 
                    <h3>Modifica le tue informazioni personali </h3>
                    <p>* UserId non è modificabile </br> Inserisci la password nel campo 'Conferma password' per poter effettuare le modifiche!</p>
                    <?php
                
                        try {
                             $user = $currentuser; 
             
                             $sql = "SELECT U.UserID, U.Nome, U.Cognome,U.Email, U.Pwd, U.DataNascita, D.IDImpresa, D.NomeImpresa, 
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
                                    <label id="errorBirthDate" style="display:none;">Data di Nascita non accettabile</label> <br/>';
                            
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

                                 echo   '<input type="submit" class="btn btn-smaller" value="submit"></form>';
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
                <input type="button" class="btn btn-smaller" onclick='closeModificaProfilo()' value="Chiudi"> </input>
            </div>
             
            <div id="divInfoUtente" class="card"> 

        <div class="container"> 
            <h4> Dati personali </h4>
                <?php
                    $connection = new connectDB();
                    $pdo = $connection->getPDO();
                    try{
                        if($usertype == 'cliente')
                        {
                            $sql = "SELECT A.UserID, A.Nome, A.Cognome, 
                                        IFNULL(B.RichiesteFatte,0) AS RichiesteFatte , IFNULL(C.RisposteRicevute,0) AS RisposteRicevute, 
                                        IFNULL(D.RisposteAccettate,0) AS RisposteAccettate, IFNULL(E.RecensioniFatte,0) AS RecensioniFatte,
                                        IFNULL(F.RecensioniRicevute,0) AS RecensioniRicevute, IFNULL(G.RatingMedio,0) AS RatingMedio
                                    FROM Utente A
                                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RichiesteFatte
                                                            FROM Richiesta
                                                            GROUP BY Utente
                                                            ) B ON A.UserID = B.Utente
                                            LEFT OUTER JOIN (SELECT B.Utente, COUNT(*) AS RisposteRicevute
                                                            FROM Risposta A 
                                                            INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta
                                                            GROUP BY B.Utente
                                                            ) C ON A.UserID = C.Utente
                                            LEFT OUTER JOIN (SELECT B.Utente, COUNT(*) AS RisposteAccettate
                                                            FROM Risposta A 
                                                            INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta
                                                            WHERE A.StatoRisposta = 'accettata'
                                                            GROUP BY B.Utente
                                                            ) D ON A.UserID = D.Utente
                                            LEFT OUTER JOIN (SELECT Recensore, COUNT(*) AS RecensioniFatte
                                                            FROM Recensione
                                                            GROUP BY Recensore
                                                            ) E ON A.UserID = E.Recensore
                                            LEFT OUTER JOIN (SELECT Recensito, COUNT(*) AS RecensioniRicevute
                                                            FROM Recensione
                                                            GROUP BY Recensito
                                                            ) F ON A.UserID = F.Recensito
                                            LEFT OUTER JOIN (SELECT Recensito, AVG(Rating) AS RatingMedio
                                                            FROM Recensione
                                                            GROUP BY Recensito
                                                            ) G ON A.UserID = G.Recensito
                                        WHERE A.UserID = :user;";
                            $statement = $pdo->prepare($sql);
                            $statement->bindValue( ':user', $currentuser);
                            $statement->execute();
                            $row = $statement->fetch();

                            echo '<p><strong>Nome:  </strong>'.$row['Nome'].'</p>'
                                .'<p><strong>Cognome: </strong>'.$row['Cognome'].'</p>'
                                .'<p><strong>Richieste Fatte: </strong>'.$row['RichiesteFatte'].'</p>'
                                .'<p><strong>Risposte Ricevute: </strong>'.$row['RisposteRicevute'].'</p>'
                                .'<p><strong>Risposte Accettate: </strong>'.$row['RisposteAccettate'].'</p>'
                                .'<p><strong>Recensioni Fatte: </strong>'.$row['RecensioniFatte'].'</p>'
                                .'<p><strong>Recensioni Ricevute: </strong>'.$row['RecensioniRicevute'].'</p>'
                                .'<p><strong>Rating Medio: </strong>'.$row['RatingMedio'].'</p>';
                        }
                        else if($usertype == 'impresa')
                        {
                            $sql = "SELECT A.UserID, A.Nome, A.Cognome, I.Nome AS NomeImpresa,
                                            IFNULL(B.RisposteFatte,0) AS RisposteFatte , IFNULL(C.RisposteAccettate,0) AS RisposteAccettate, 
                                            IFNULL(D.RisposteRifiutate,0) AS RisposteRifiutate, IFNULL(E.RecensioniFatte,0) AS RecensioniFatte,
                                            IFNULL(F.RecensioniRicevute,0) AS RecensioniRicevute, IFNULL(G.RatingMedio,0) AS RatingMedio
                                    FROM Utente A INNER JOIN Impresa I ON A.UserID = I.Responsabile
                                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RisposteFatte
                                                            FROM Risposta
                                                            GROUP BY Utente
                                                            ) B ON A.UserID = B.Utente
                                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RisposteAccettate
                                                            FROM Risposta 
                                                            WHERE StatoRisposta = 'accettata'
                                                            GROUP BY Utente
                                                            ) C ON A.UserID = C.Utente
                                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RisposteRifiutate
                                                            FROM Risposta A 
                                                            WHERE StatoRisposta = 'rifiutata'
                                                            GROUP BY Utente
                                                            ) D ON A.UserID = D.Utente
                                            LEFT OUTER JOIN (SELECT Recensore, COUNT(*) AS RecensioniFatte
                                                            FROM Recensione
                                                            GROUP BY Recensore
                                                            ) E ON A.UserID = E.Recensore
                                            LEFT OUTER JOIN (SELECT Recensito, COUNT(*) AS RecensioniRicevute
                                                            FROM Recensione
                                                            GROUP BY Recensito
                                                            ) F ON A.UserID = F.Recensito
                                            LEFT OUTER JOIN (SELECT Recensito, AVG(Rating) AS RatingMedio
                                                            FROM Recensione
                                                            GROUP BY Recensito
                                                            ) G ON A.UserID = G.Recensito
                                    WHERE A.UserID = :user;";
                            $statement = $pdo->prepare($sql);
                            $statement->bindValue( ':user', $currentuser);
                            $statement->execute();
                            $row = $statement->fetch();

                            echo '<p><strong>Nome Impresa :  </strong>'.$row['NomeImpresa'].'</p>'
                                .'<p><strong>Nome responsabile :  </strong>'.$row['Nome'].'</p>'
                                .'<p><strong>Cognome responsabile: </strong>'.$row['Cognome'].'</p>'
                                .'<p><strong>Risposte inviate: </strong>'.$row['RisposteFatte'].'</p>'
                                .'<p><strong>Risposte Accettate: </strong>'.$row['RisposteAccettate'].'</p>'
                                .'<p><strong>Risposte Rifiutate: </strong>'.$row['RisposteRifiutate'].'</p>'
                                .'<p><strong>Recensioni Fatte: </strong>'.$row['RecensioniFatte'].'</p>'
                                .'<p><strong>Recensioni Ricevute: </strong>'.$row['RecensioniRicevute'].'</p>'
                                .'<p><strong>Rating Medio: </strong>'.$row['RatingMedio'].'</p>';
                        }
                    }
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle informazioni personali </br>";
                            echo $emess;
                        }
                    
                    $connection->close();
                    $pdo = null;
                ?>
            <div class="separatore"></div>



        </div>
        </div>


           <div id="divRecensionifatte" >
               <h3>Recensioni scritte</h3>
               <?php
            
                    $connection = new connectDB();
                    $pdo = $connection->getPDO();
                     try{
                         
                         $user = $currentuser;

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
                                    echo '<div id="divRecensione'. $row['IDRecensione'].'" class="card">'.
                                        '<div class="container">'. 
                                        '<h4> Recensione #'.$row['IDRecensione'].'</h4>'
                                        .'<p><strong>Servizio offerto da: </strong>'.$row['Recensito'].'</p>'
                                        .'<p><strong>Tipo di mobile: </strong>'.$row['TipoMobile'].'</p>'
                                        .'<p><strong>Rating: </strong>'.$row['Rating'].'</p>'
                                        .'<p><strong>Testo: </strong>'.$row['TestoRecensione'].'</p>'
                                        .'</div></div><div class="separatore"></div>';
                                } while( $row = $statement->fetch() );
                            }
                            else {//user è impresa
                                do {
                                        echo '<div id="divRecensione'. $row['IDRecensione'].'" class="card">'.
                                            '<div class="container">'. 
                                            '<h4> Recensione #'.$row['IDRecensione'].'</h4>'
                                            .'<p><strong>Cliente: </strong>'.$row['Recensito'].'</p>'
                                            .'<p><strong>Tipo di mobile: </strong>'.$row['TipoMobile'].'</p>'
                                            .'<p><strong>Rating: </strong>'.$row['Rating'].'</p>'
                                            .'<p><strong>Testo: </strong>'.$row['TestoRecensione'].'</p>'
                                            .'</div></div><div class="separatore"></div>';
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

           <div id="divRecensioniricevute" >
               <h3>Recensioni ricevute</h3>
               <?php

                    $connection = new connectDB();
                    $pdo = $connection->getPDO();
                    try{
                        $user = $currentuser;

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
                                    echo '<div id="divRecensione'. $row['IDRecensione'].'" class="card">'.
                                        '<div class="container">'. 
                                        '<h4> Recensione #'.$row['IDRecensione'].'</h4>'
                                        .'<p><strong>Impresa: </strong>'.$row['Recensore'].'</p>'
                                        .'<p><strong>Tipo di mobile: </strong>'.$row['TipoMobile'].'</p>'
                                        .'<p><strong>Rating: </strong>'.$row['Rating'].'</p>'
                                        .'<p><strong>Testo: </strong>'.$row['TestoRecensione'].'</p>'
                                        .'</div></div><div class="separatore"></div>';
                                } while( $row = $statement->fetch() );
                            }
                            else {//user è impresa
                                do {
                                    echo '<div id="divRecensione'. $row['IDRecensione'].'" class="card">'.
                                        '<div class="container">'. 
                                        '<h4> Recensione #'.$row['IDRecensione'].'</h4>'
                                        .'<p><strong>Servizio offerto per: </strong>'.$row['Recensore'].'</p>'
                                        .'<p><strong>Tipo di mobile: </strong>'.$row['TipoMobile'].'</p>'
                                        .'<p><strong>Rating: </strong>'.$row['Rating'].'</p>'
                                        .'<p><strong>Testo: </strong>'.$row['TestoRecensione'].'</p>'
                                        .'</div></div><div class="separatore"></div>';
                                } while( $row = $statement->fetch() );
                            }
                        } 
                        else {
                            echo "Non hai ancora ricevuto nessuna recensione.";
                        }                      
                    }  
            
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle recensioni ricevute </br>";
                            echo $emess;
                        }
                    
                    $connection->close();
                    $pdo = null;
               ?>
           </div>  

  </div>

</body>
</html>