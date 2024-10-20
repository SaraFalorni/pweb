<?php

class connectDB{
    public $pdo;
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function __construct(){
        try {
            $connectionString = "mysql:host=127.0.0.1;dbname=Falorni_596588";
            $user = "root";
            $pass = "";

            
            $this->pdo = new PDO($connectionString, $user, $pass);

        } catch (PDOException $e) {

			die($e->getMessage());
			
        }
    }

    function getPDO(){
        return $this->pdo;
    }

    function close(){
        $this->pdo = null;
    }
}

?>
