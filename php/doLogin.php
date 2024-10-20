<?php 

include './connectDB.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try {
    
    $user = $_GET['user'];
    $pwd = $_GET['pwd'];

    $sql = "SELECT U.Pwd FROM Utente U WHERE U.UserID = :userid";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':userid', $user);
    $statement->execute();
    $pass = $statement->fetch();

    if (!$pass) {
        echo '<style> body {background-color: rgb(255, 255, 154);} </style>';
        echo "User ID non esistente </br>";
        echo "Usa il pulsante per tornare al Login!";
        echo ' <a href="./login.php" > <button>Click Me!</button> </a>';
    }
    else {
        echo $pwd . "</br>";
        echo $pass['Pwd'] . "</br>";
        if( $pwd != $pass['Pwd']) {
            echo '<style> body {background-color: rgb(255, 255, 154);} </style>';
            echo "Password scorretta </br>";
            echo "Usa il pulsante per tornare al Login!";
            echo ' <a href="./login.php" > <button>Click Me!</button> </a>';
        }
        else{
      
            $sql = "SELECT Responsabile 
                    FROM Impresa
                    WHERE Responsabile = :userid ";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':userid', $user);
            $statement->execute();
            $row = $statement->fetch();

            $userlogged = "user";
            setcookie($userlogged, $user);

            $userloggedtype = "usertype";
            

            if($row == NULL) {
                //utente è un cliente
                $cliente = 'cliente';
                setcookie($userloggedtype, $cliente);
                header("Location:indexCliente.php");
                exit();
            }
            else {
                //utente è un'impresa
                $impresa = 'impresa';
                setcookie($userloggedtype, $impresa);
                header("Location:indexImpresa.php");
                exit();
            }
        }
    }

}
catch(PDOException | Exception $e) {
    $emess = $e->getMessage();
        $erroreinserimento = "C'è stato un errore nell'accesso area personale. Ritenta' </br>";
        echo $erroreinserimento ;
        echo $emess;
    }
    
    
    
    $connection->close();
    $pdo = null;
    
    ?>