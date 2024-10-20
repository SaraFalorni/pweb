<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <script type="text/javascript" src="../js/getRisposte.js"></script>
    </head>
<body>
    <header>
    
    <img src="logo.png" alt="Logo"> <span>&nbsp;My website</span>

   </header>
   <nav>
    <ul>
      <li><a href="./indexCliente.php">Home</a></li>
      <li><a href="./lookForRisposte.php" id="lookForRisp" >Risposte ricevute</a></li>
      <li><a href="./archivio.php">Archivio Richieste</a></li>
      <li><a href="./areaPersonale.php">Area Personale</a></li>
      <li><a href="./NuovaRichiesta.php">Nuova richiesta</a></li>
      <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>
    </ul>
  </nav>
  <hr>
    <h1>Risposte</h1> <br/>
    Filtra le risposte ricevute selezionando una richiesta tra quelle fatte e non ancora concluse: </br>
    <ul>
        <?php 

            include '../php/connectDB.php';

            $connection = new connectDB();
            $pdo = $connection->getPDO();

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
                                <input type="button" onclick="GetRisposte(' . $row['IDRichiesta'] . ')" id="buttonRisposte"> </input>
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

    ?>
    </ul>
  
    

</body>
</html>