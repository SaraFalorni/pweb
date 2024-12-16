<?php

include '../php/connectDB.php';
include '../php/getuser.php';
                ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <link rel="stylesheet" type="text/css" href="../css/lookForRequest.css"> 
        <script type="text/javascript" src="../js/getRequest.js"></script> 
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
  <div id = "mainContents" class="mainContent"> 
    <h1>Richieste</h1>
    <div>
        <label for="areatype" >Mostra richieste disponibili nella tua</label>
        <select name="areatype" id="areatype" >
            <option disabled selected value> -- Scegli l'area -- </option>
            <option value="Regione"> Regione </option>
            <option value="Provincia"> Provincia </option>
            <option value="Comune"> Città </option>
        </select>
    &nbsp;
        <input class="btn" type="button" id="btnaggiorna" onclick="RequestsinArea(<?php echo ' \' ' . $currentuser . ' \' '?>)" value="Cerca" />
    </div>
    <div id="divrichieste">
</body>
</html>