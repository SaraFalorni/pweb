<?php

include '../php/connectDB.php';
include '../php/getuser.php';
include '../php/class.utente.php';
include '../php/class.richiesta.php';
include '../php/class.risposta.php';
include '../php/class.recensione.php';
include '../php/utilityFunctions.php';
                ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <link rel="stylesheet" type="text/css" href="../css/LookForRisposte.css">
        <script type="text/javascript" src="../js/areaPersonale.js"></script>
        <script type="text/javascript" src="../js/signUp.js"></script>
        <script type="text/javascript" src="../js/location.js"></script> 
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
  <h2>Area Personale</h2> <br/>
        <?php $utente = new Utente($currentuser); ?>
  

   <p> <input type="button" class="btn" onclick = 'ModificaProfilo()' value="Modifica Profilo"> 
       <input type="hidden" id="usertype" value="<?php echo $usertype ?>"> </input> </p>
            <div id="divModificaProfilo" style="display:none;" >
                <p>Le tue informazioni personali: </p>
                <form action="./modificaProfilo.php" method="POST">
            <?php
                try {
                    $utente = new Utente($currentuser);

                    $utente->mostraModificaInfo();

                    if($utente->impresa) {
                        //se l'utente è registrato come impresa mostra anche le informazione di quest'ultima
                        $utente->impresa->mostraModificaInfo();
                    }
                }
                catch(PDOException | Exception $e) {
                    $emess = $e->getMessage();
                     $erroreinserimento = "C'è stato un errore nell'accedere alle informazioni personali </br>";
                     echo $emess;
                }
            ?>
                   <input type="submit" class="btn btn-smaller" value="Invia modifiche">
                   <input type="button" class="btn btn-smaller" onclick = 'CloseModificaProfilo()' value="Chiudi"> 
                </form>
            </div>
               
        <div id="divInfoUtente" class="card"> 

        <div class="container"> 
            <h4> Dati personali </h4>
                <?php
                    try{

                        if( $usertype == 'cliente') {
                            getDatiPersonaliCliente($currentuser);
                        }
                        else {
                            //user è impresa
                            getDatiPersonaliImpresa($currentuser);
                        }  
                    }
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle informazioni personali </br>";
                            echo $emess;
                        }
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
                        getRecensioniFatte($currentuser, $usertype);  
                    }
             
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle recensioni fatte </br>";
                            echo $emess;
                    }
               ?>
               
           </div>  

           <div id="divRecensioniricevute" >
               <h3>Recensioni ricevute</h3>
               <?php

                    $connection = new connectDB();
                    $pdo = $connection->getPDO();
                    try {
                        getRecensioniRicevute($currentuser, $usertype);                  
                    }  
            
                    catch(PDOException | Exception $e) {
                        $emess = $e->getMessage();
                            $erroreinserimento = "C'è stato un errore nell'accedere alle recensioni ricevute </br>";
                            echo $emess;
                    }
               ?>
           </div>  

  </div>

</body>
</html>