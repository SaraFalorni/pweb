<?php 

    include '../php/connectDB.php';
    include '../php/getuser.php';
      ?>    
          
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <!--<script type="text/javascript" src="../js/signUp.js"></script> -->
    </head>
<body>
<header>
    
    <img class="imglogo" src="../img/logo.png" alt="Logo" > <span>&nbsp;</span>
<span class="usrwelcome">Benvenuto utente: <?php echo $currentuser . ' [' . $usertype . ']' ?></span>
  </header>
   <nav>
    <ul>
      <li><a href="./indexCliente.php">Home</a></li>
      <li><a href="./LookForRisposte.php">Risposte ricevute</a></li>
      <li><a href="./archivio.php">Archivio Richieste</a></li>
      <li><a href="./areaPersonale.php">Area Personale</a></li>
      <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
      <li><a href="../index.html" id="logoutButton"> Logout </a></li>
    </ul>
  </nav>
  <hr>
  <h1>Homepage cliente</h1> <br/>
  <div class="mainContent"> 

  
  </div>
	

</body>
</html>