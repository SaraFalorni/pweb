<?php 

include './connectDB.php';
include './utility.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try {

    $clientValues = ['UserID', 'nome', 'cognome','email','pwd','cpwd','birthDate'];

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
    $birthday = new DateTime($_POST['birthDate']);
    $today = new DateTime();
    $age = $today->diff($birthday)->y;
    if ($age < 18) {
        throw new Exception("data di nascita non valida");
     }

    

    //prendo i dati inseriti dall'utente
    $UserID = $_POST['UserID'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $cpwd = $_POST['cpwd'];
    $birthDate = $_POST['birthDate'];


    $idimpresa = NULL;
    
    //se è con impresa va inserita anche l'impresa nel database
    

    //sicurezza password
    $salt = generateRandomSalt();

    $sql = "INSERT INTO Utente VALUES (:userID, :nome, :cognome, :email, MD5(:pwd), :birthdate, :salt)";

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':userID', $UserID);
    $statement->bindValue( ':nome', $nome);
    $statement->bindValue( ':cognome', $cognome);
    $statement->bindValue( ':email', $email);
    $statement->bindValue( ':pwd', $pwd.$salt);
    $statement->bindValue( ':birthdate', $birthDate);
    $statement->bindValue( ':salt', $salt);
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

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <link rel="stylesheet" type="text/css" href="../css/LookForRisposte.css">
        <link rel="stylesheet" type="text/css" href="../css/login.css">
    
        <script type="text/javascript" src="../js/location.js"></script> 
    </head>
<body>
<header>
    <img class="imglogo" src="../img/logo.png" alt="Logo" > <span>&nbsp;</span>
</header>
   <nav>
    <ul>
      <li><a href="..../index.html">Home</a></li>
      <li><a href="./signUp.html">Registrati</a></li>
      <li><a href="../html/login.html">Accedi</a></li>
    </ul>
  </nav>
  <div class="loginmainContent">
    <div class="card" style="position">
        <div class="container">
            <p> La tua registrazione è andata a buon fine. </br> Clicca <a href="../html/login.html">qui</a> per fare il login. </p>
        </div>
    </div>
    
  </div>

