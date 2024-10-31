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
        <script type="text/javascript" src="../js/getRisposte.js"></script>
    </head>
<body>
<header>
    <img class="imglogo" src="../img/logo.png" alt="Logo" > <span>&nbsp;</span>
    <span class="usrwelcome">Benvenuto utente: <?php echo $currentuser . ' [' . $usertype . ']' ?></span>
</header>
   <nav>
    <ul>
      <li><a href="./indexCliente.php">Home</a></li>
      <li><a href="./lookForRisposte.php" id="lookForRisp" >Risposte ricevute</a></li>
      <li><a href="./archivio.php">Archivio Richieste</a></li>
      <li><a href="./areaPersonale.php">Area Personale</a></li>
      <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
      <li><a href="../index.html" id="logoutButton"> Logout </a></li>
    </ul>
  </nav>
  <hr>

  <div class="mainContent"> 
    <h1>Risposte</h1> <br/>
    Filtra le risposte ricevute selezionando una richiesta tra quelle fatte e non ancora concluse: </br>
    <ul>
        <?php 
/*
            include '../php/connectDB.php';

            $connection = new connectDB();
            $pdo = $connection->getPDO();
*/
/*
            try{

                if(!isset($_COOKIE["user"])) {
                    echo "cookie non settato </br>" ;
                }
                else {
                $user = $_COOKIE["user"];
                $sql = "SELECT * FROM Richiesta R INNER JOIN Comune C ON R.Comune = C.id 
                        WHERE Utente = :user AND StatoRichiesta = 'inviata' ";
                $statement = $pdo->prepare($sql);
                $statement->bindValue( ':user', $user);
                $statement->execute();
                $row = $statement->fetch();
                    if($row) {
                        do { #sistemare
                                echo '<li id= "'. $row['IDRichiesta'] .'" name="rRichieste">
                                 <div >
                                Richiesta per il montaggio di ' .$row['TipoMobile']. ' nella data '.$row['DataRichiesta'].
                                ' nella fascia oraria ' .$row['FasciaOraria']. ' nel comune di ' .$row['Comune']. ' 
                                <input class="btn-secondario" type="button" onclick="GetRisposte(' . $row['IDRichiesta'] . ')" id="buttonRisposte" value="&raquo; ' . $row['IDRichiesta'] . '"> </input>
                                <div id="divRisposte'.$row['IDRichiesta'].'"></div>
                                </div>  </li> ' ;
                        } while( $row = $statement->fetch());
                    }
                } 
            }  

            catch(PDOException | Exception $e) {
                $emess = $e->getMessage();
                $erroreinserimento = "C'è stato un errore nell'ottenere le richieste </br>";
                echo $erroreinserimento ;
                echo $emess;
            }

            $connection->close();
            $pdo = null;
            */

    ?>
    </ul>
        <!-- *** -->



        <?php
        /*
                   $connection = new connectDB();
                   $pdo = $connection->getPDO();
    */
                   try{
       
                       if(!isset($_COOKIE["user"])) {
                           echo "cookie non settato </br>" ;
                       }
                       else {
                       $user = $_COOKIE["user"];
                       $sql = "SELECT * FROM Richiesta R INNER JOIN Comune C ON R.Comune = C.id 
                               WHERE Utente = :user AND StatoRichiesta = 'inviata' ORDER BY DataRichiesta desc";
                       $statement = $pdo->prepare($sql);
                       $statement->bindValue( ':user', $user);
                       $statement->execute();
                       $row = $statement->fetch();
                           if($row) {
                               do { 

echo '<div class="card">
  <div class="container">
    <h4><b>Richiesta</b> #'.$row['IDRichiesta'].'</h4> 
    <p><strong>Tipo Mobile</strong>: '.$row['TipoMobile'].'</p> 
    <p><strong>Data</strong>: '.$row['DataRichiesta'].'&nbsp;&nbsp;&nbsp;<strong>Fascia Oraria</strong>: '.$row['FasciaOraria'].'</p>
    <p><strong>Localizzazione</strong>: '.$row['Comune'].'/'.$row['Provincia'].'/'.$row['Regione'].'</p>
<button id="'.$row['IDRichiesta'].'" class="accordion">Risposta</button>
<div class="panel">
<div id="pnlRisposta'.$row['IDRichiesta'].'"><p>&nbsp;</p></div>
</div></div></div><div class="separatore"></div>';



                               } while( $row = $statement->fetch());
                           }
                       } 
                   }  
       
                   catch(PDOException | Exception $e) {
                       $emess = $e->getMessage();
                       $erroreinserimento = "C'è stato un errore nell'ottenere le richieste </br>";
                       echo $erroreinserimento ;
                       echo $emess;
                   }
       
                   $connection->close();
                   $pdo = null;
       
           ?>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
    // Predispone il caricamento della risposta
    //console.log(this);
    GetRisposte(this.id);
  });
}
</script>


    </div>  

</body>
</html>