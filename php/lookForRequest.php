<?php

include '../php/connectDB.php';
include '../php/getuser.php';
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
    <h1>Richieste</h1>
    <div>
    <label for="area" >Mostra richieste disponibili nella tua</label>
    <select name="area" id="area" >
        <option disabled selected value> -- Scegli l'area -- </option>
        <option value="Regione"> Regione </option>
        <option value="Provincia"> Provincia </option>
        <option value="Comune"> Città </option>
    </select>
    &nbsp;
    <input class="btn" type="button" id="btnaggiorna" onclick="RequestsinArea()" value="Cerca" />
    </div>
    <div id="richieste">
    </div>
    <div id="dRisposta" style="display:none">
    <form id="formRisposta" action="./sendRisposta.php" method="POST">
        Scrivi qualcosa che vuoi far sapere al cliente! (richieste particolari, messaggi etc.) <br/>
        <textarea type="textarea" id="inRisposta" name="inRisposta"> </textarea> <br/>
        <input type="hidden" id="selRichiesta" name="selRichiesta"  > </input>
        <input type="hidden" id="selUser" name="selUser" > </input>
        <button type="submit" class="btn btn-smaller" onclick="sendRisposta()" >Invia</button> &nbsp; 
        <button type="button" class="btn btn-smaller" onclick="closeFormRisp()">Chiudi</button>
    </form>
    </div>
    
    
  </div>
</body>
</html>