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
        <!-- <script type="text/javascript" src="../js/getRisposte.js"></script>-->
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
<?php 


$connection = new connectDB();
$pdo = $connection->getPDO();

try{
    $utente = $_GET['utente'];

    echo '<h1>Recensioni ricevute da '.$utente.' </h1> <br/>';

    $sql = " SELECT A.IDRecensione, A.Recensore, A.TestoRecensione, A.Rating, B.TipoMobile
             FROM Recensione A INNER JOIN Richiesta B ON A.RichiestaRecensita = B.IDRichiesta
             WHERE Recensito = :utente";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':utente', $utente);
    $statement->execute();
    $row = $statement->fetch();
    if($row) {
        do {
            echo '<div id="divRecensione'. $row['IDRecensione'].'" class="card">'.
            '<div class="container">'. 
             '<h4> Recensione #'.$row['IDRecensione'].'</h4>'
             .'<p><strong>Recensore: </strong>'.$row['Recensore'].'</p>'
             .'<p><strong>Tipo di mobile: </strong>'.$row['TipoMobile'].'</p>'
             .'<p><strong>Rating: </strong>'.$row['Rating'].'</p>'
             .'<p><strong>Testo: </strong>'.$row['TestoRecensione'].'</p>'
             .'</div></div><div class="separatore"></div>';

        } while( $row = $statement->fetch());
    }
    else {
        echo '<p> L\'utente '. $utente .' non ha ancora ricevuto recensioni. </p>';
    }
}

catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
     $erroreinserimento = "C'è stato un errore nella ricerca delle recensioni</br>";
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
     // Predispone il caricamento della risposta
    GetRisposte(this.id);
    //
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
        panel.style.maxHeight = "300px"; // panel.scrollHeight + "px";
    } 
  });
}
</script>


    </div>  

</body>
</html>