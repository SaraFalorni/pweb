<?php 

include './connectDB.php';
include '../php/getuser.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try {

    $clientValues = ['nome', 'cognome','email','pwd','cpwd','birthDate'];

    foreach($clientValues as $cv) {
        if($_POST[$cv] == '') {
            throw new Exception("no input nel campo $cv ");
        }
    };

    $sql = "SELECT pwd, salt FROM Utente WHERE UserID = :userID";

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':userID', $currentuser);
    $statement->execute();
    $row = $statement->fetch();
    $salt = $row['salt'];
    $oldpwdsalted = $row['pwd'];

    $pwd = $_POST['pwd'];
    $cpwd = $_POST['cpwd'];

    $sql = "SELECT MD5(:pwd) AS Newpwdsalted, MD5(:cpwd) AS Newcpwdsalted";

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':pwd', $pwd.$salt);
    $statement->bindValue( ':cpwd', $cpwd.$salt);
    $statement->execute();
    $row = $statement->fetch();

    $newpwdsalted = $row['Newpwdsalted'];
    $newcpwdsalted = $row['Newcpwdsalted'];
    
    //verifica che gli input siano nel formato voluto
    if (strlen($pwd) < 7) {
        throw new Exception("password troppo corta");
    }
    if ($pwd != $cpwd ) {
       throw new Exception("conferma password errata");
    }
    if ($pwd == $cpwd && $newpwdsalted != $oldpwdsalted) {
        throw new Exception("password errata");
    }

    //prendo i dati inseriti dall'utente
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $birthDate = $_POST['birthDate'];
    $foto = $_POST['foto'];

    $idimpresa = NULL;
    
    //se è con impresa va inserita anche l'impresa nel database
    
    

    $sql = "UPDATE Utente SET Nome = :nome, Cognome = :cognome, Email = :email, Pwd = MD5(:pwd), DataNascita = :birthdate WHERE UserID = :userID";

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':userID', $currentuser);
    $statement->bindValue( ':nome', $nome);
    $statement->bindValue( ':cognome', $cognome);
    $statement->bindValue( ':email', $email);
    $statement->bindValue( ':pwd', $pwd.$salt);
    $statement->bindValue( ':birthdate', $birthDate);
    $statement->execute();

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

        $sql = "UPDATE Impresa SET  Nome = :nomeimpresa, Descrizione = :bio, Comune = :comune  WHERE Responsabile = :UserID";

        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':nomeimpresa', $nomeimpresa);
        $statement->bindValue( ':bio', $bio);
        $statement->bindValue( ':comune', $comune);
        $statement->bindValue( ':UserID', $currentuser);
        $statement->execute();

    }
    
    header("Location:areaPersonale.php");
    exit();

}

catch(PDOException | Exception $e) {
   $emess = $e->getMessage();
    $erroreinserimento = "C'è stato un errore nella modifica delle informazioni dell'utente </br>";
    echo $erroreinserimento ;
    echo $emess;
}



$connection->close();
$pdo = null;

?>