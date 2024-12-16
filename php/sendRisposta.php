<?php 

include '../php/connectDB.php';
include '../php/getuser.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try{

    $idRic = $_POST['selRichiesta'];
    //$idUser= $_POST['selUser'];
    $risp= $_POST['inRisposta'];
    $stato = 'inviata';

    //inserisce una nuova risposta 

    $sql = "INSERT INTO Risposta (MessaggioRisposta,StatoRisposta,Richiesta,Utente) VALUES (:risp, :stato, :idRic, :idUser) ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':risp', $risp);
    $statement->bindValue(':stato', $stato);
    $statement->bindValue(':idRic', $idRic);
    $statement->bindValue(':idUser', $currentuser);
    $statement->execute();

}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nell'inviare la risposta </br>";
     echo $erroreinserimento ;
     echo $emess;
 }

$connection->close();
$pdo = null;

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <script type="text/javascript" src="../js/getRequest.js"></script> 
    </head>
<body>
<header>
    <img class="imglogo" src="../img/logo.png" alt="Logo" > <span>&nbsp;</span>
    <span class="usrwelcome">Benvenuto utente: <?php echo $currentuser . ' [' . $usertype . ']' ?></span>  
</header>
   <nav>
    <ul>
      <li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
      <li><a href="./archivio.php">Archivio Risposte</a></li>
      <li><a href="./areaPersonale.php">Area Personale</a></li>
      <li><a href="../index.html" id="logoutButton"> Logout </a></li>
    </ul>
  </nav>
  <hr>
  <div class="mainContent"> 
    <h1>Risposta inviata con successo!</h1>
    <div>
        Per monitorare l'esito della tua proposta, hai a disposizione tutte le funzionalità nell' <a href="./archivio.php">archivio risposte.</a>
    </div>
</body>
</html>