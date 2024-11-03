<?php 

include './connectDB.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try {

    $clientValues = ['UserID', 'nome', 'cognome','email','pwd','cpwd','birthDate','indirizzo'];

    foreach($clientValues as $cv) {
        if($_POST[$cv] == '') {
            throw new Exception("no input nel campo $cv ");
        }
    };
    
    //verifica che gli input siano nel formato voluto
    if (!$_POST['UserID']) {
        throw new Exception("Credenziali sbagliate");
    }
    
    if (strlen($_POST['UserID']) < 5) {
        throw new Exception("UserID troppo corto");
    }
    if (strlen($_POST['pwd']) < 7) {
        throw new Exception("password troppo corta");
    }
    if ($_POST['pwd'] != $_POST['cpwd'] ) {
       throw new Exception("conferma password errata");
    }

       

    //prendo i dati inseriti dall'utente
    $UserID = $_POST['UserID'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $cpwd = $_POST['cpwd'];
    $birthDate = $_POST['birthDate'];
    $indirizzo = $_POST['indirizzo'];
    $foto = $_POST['foto'];

    $idimpresa = NULL;
    
    //se è con impresa va inserita anche l'impresa nel database
    
    

    $sql = "INSERT INTO Utente VALUES (:userID, :nome, :cognome, :email, :pwd, :birthdate, :indirizzo, :foto)";

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':userID', $UserID);
    $statement->bindValue( ':nome', $nome);
    $statement->bindValue( ':cognome', $cognome);
    $statement->bindValue( ':email', $email);
    $statement->bindValue( ':pwd', $pwd);
    $statement->bindValue( ':birthdate', $birthDate);
    $statement->bindValue( ':indirizzo', $indirizzo);
    $statement->bindValue( ':foto', $foto);
    $statement->execute();

    $usertype = $_POST['UserType'];
    echo $usertype." in più </br>";
    if( $usertype == 'impresa') {

        $clientValues = ['nomeImpresa','Comune'];

        foreach($clientValues as $cv) {
            if($_POST[$cv] == '') {
                throw new Exception("no input nel campo $cv </br>");
            }
        };

        /*if (!$_POST['nomeImpresa']) {
            throw new Exception("Credenziali sbagliate </br>");
        }*/

        $nomeimpresa = $_POST['nomeImpresa'];
        $comune = $_POST['Comune'];
        $bio = $_POST['bio'];

        $sql = "INSERT INTO Impresa (Nome,Descrizione,Comune,Responsabile)  VALUES ( :nomeimpresa, :bio, :comune, :UserID )";

        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':nomeimpresa', $nomeimpresa);
        $statement->bindValue( ':bio', $bio);
        $statement->bindValue( ':comune', $comune);
        $statement->bindValue( ':UserID', $UserID);
        $statement->execute();

    }

    echo "todo bien! estoy encantada </br>";

}

catch(PDOException | Exception $e) {
   $emess = $e->getMessage();
    $erroreinserimento = "C'è stato un errore nella registrazione di un nuovo utente </br>";
    echo $erroreinserimento ;
    echo $emess;
}



$connection->close();
$pdo = null;

?> 