<?php 

include './connectDB.php';
include './utility.php';

$connection = new connectDB();
$pdo = $connection->getPDO();

try {
    
    $user = $_GET['user'];
    $pwd = $_GET['pwd'];

    $sql = "SELECT  U.salt FROM Utente U WHERE U.UserID = :userid ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':userid', $user);
    $statement->execute();
    $row = $statement->fetch();// se è null non esiste l'utente

    $salt = $row['salt'];

    $sql1 = "SELECT  UserID FROM Utente U WHERE U.Pwd = MD5(:pwdsalt) AND U.UserID = :userid ";
    $statement1 = $pdo->prepare($sql1);
    $statement1->bindValue(':pwdsalt', $pwd.$salt);
    $statement1->bindValue(':userid', $user);
    $statement1->execute();
    $row1 = $statement1->fetch(); //se è null la password è scorretta

    if ($row['salt'] == NULL) {
        echo '<style> body {background-color: rgb(255, 255, 154);} </style>';
        echo "User ID non esistente </br>";
        echo "Usa il pulsante per tornare al Login!";
        echo ' <a href="./login.php" > <button>Click Me!</button> </a>';
    }
    else if( $row1['UserID'] == NULL ) {
            echo '<style> body {background-color: rgb(255, 255, 154);} </style>';
            echo "Password scorretta </br>";
            echo "Usa il pulsante per tornare al Login!";
            echo ' <a href="./login.php" > <button>Click Me!</button> </a>';
    }
        
    else{
      
            $sql2 = "SELECT Responsabile 
                    FROM Impresa
                    WHERE Responsabile = :userid ";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindValue(':userid', $user);
            $statement2->execute();
            $row2 = $statement2->fetch();

            $userlogged = "user";
            setcookie($userlogged, $user);

            $userloggedtype = "usertype";
            

            if($row2['Responsabile'] == NULL) {
                //utente è un cliente
                $cliente = 'cliente';
                setcookie($userloggedtype, $cliente);
                header("Location:./LookForRisposte.php");
                exit();
            }
            else {
                //utente è un'impresa
                $impresa = 'impresa';
                setcookie($userloggedtype, $impresa);
                header("Location:./lookForRequest.php");
                exit();
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