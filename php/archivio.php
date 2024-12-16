<?php

include '../php/connectDB.php';
include '../php/getuser.php';
include '../php/class.richiesta.php';
include '../php/class.risposta.php';
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
        if($usertype == 'cliente') {
            $richiesteUtente = new RichiesteUtente($currentuser);
            $richiesteUtente->OutputRichiesteUtenteArchivio();
        }
        else { //utente è impresa
            $risposteUtente = new RisposteUtente($currentuser);
            $risposteUtente->OutputRisposteUtenteArchivio();
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