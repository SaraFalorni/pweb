<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <!--<script type="text/javascript" src="../js/.js"></script>-->
    </head>
<body>
    <header>
    
    <img src="logo.png" alt="Logo"> <span>&nbsp;My website</span>

  </header>
   <nav>
    <ul>
        <!-- index in base al tipo di utente -->
        <?php
                if(!isset($_COOKIE["usertype"])) {
                    echo "cookie non settato </br>" ;
                }
                else {
                $usertype = $_COOKIE["usertype"]; }  

                if($usertype == 'cliente') {
                    //index cliente
                    echo '<li><a href="./indexCliente.php">Home</a></li>
                    <li><a href="./LookForRisposte.php">Risposte ricevute</a></li>
                    <li><a href="./archivio.php">Archivio Richieste</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
                    <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>';
                }
                else if($usertype == 'impresa') {
                    //index impresa
                    echo '<li><a href="./indexImpresa.php">Home</a></li>
                    <li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
                    <li><a href="./areaPersonale.php">Archivio Risposte</a></li>
                    <li><a href="./areaPersonale.php">Area Personale</a></li>
                    <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>';
                }
            
        ?>
  </ul>
  </nav>
  <hr>
  <h1>Archivio</h1> <br/>
  <div class="mainContent"> 


    <?php 

    include '../php/connectDB.php';

    $connection = new connectDB();
    $pdo = $connection->getPDO();

    try{
        if(!isset($_COOKIE["usertype"])) {
            echo "cookie non settato </br>" ;
        }
        else {
            $usertype = $_COOKIE["usertype"]; }   
        
        if(!isset($_COOKIE["user"])) {
            echo "cookie non settato </br>" ;
        }
        else {
            $user = $_COOKIE["user"]; }   

        //se è un'impresa
        
        $sql = "SELECT * FROM Risposta WHERE Utente = :utente"; //order by timestamp??? così sono in ordine cronologico
        if( $usertype == "cliente" ) //se è un cliente 
        {
            $sql = "SELECT * FROM Richiesta WHERE Utente = :utente"; //order by timestamp??? così sono in ordine cronologico
        }
                
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':utente', $user);
        $statement->execute();
        if($usertype == "cliente") {
            $row = $statement->fetch();
            do {
            // echo $row['']; //cosa scrivo nell'archivio??
            } while($row = $statement->fetch());
        }
        else {
            $row = $statement->fetch();
            do {
                //echo $row['']; //cosa scrivo nell'archivio??
            } while($row = $statement->fetch());
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