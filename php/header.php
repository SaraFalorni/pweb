<!-- index in base al tipo di utente -->
<?php
        if(!isset($_COOKIE["usertype"])) {
            echo "cookie non settato </br>" ;
        }
        else {
        $usertype = $_COOKIE["usertype"]; }  

        if($usertype == 'cliente') {
            //index cliente
            echo   '<li><a href="./LookForRisposte.php">Risposte ricevute</a></li>
                    <li><a href="./archivio.php">Archivio Richieste</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
                    <li><a href="./deleteCookie.php" id="logoutButton" > Logout </a></li>';
        }
        else if($usertype == 'impresa') {
            //index impresa
            echo   '<li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
                    <li><a href="./areaPersonale.php">Archivio Risposte</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="./deleteCookie.php" id="logoutButton" > Logout </a></li>';
        }
    
?>


  