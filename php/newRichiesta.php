<?php 

    include '../php/connectDB.php';
    
    $connection = new connectDB();
    $pdo = $connection->getPDO();
    
    try{

        if(!isset($_COOKIE["user"])) {
            echo "cookie non settato </br>" ;
        }
        else {
        $user = $_COOKIE["user"]; }

        //verifica che i campi obbligatori siano compilati
        $values = ['iTipoMobile', 'iDataRic', 'selFasciaOraria', 'selRegione' ,'Provincia','selComune'];

        foreach($values as $cv) {
            if($_POST[$cv] == '') {
                throw new Exception("no input nel campo obbligatorio $cv ");
            }
        };


        //verifica che la data sia futura
       $dataRic = $_POST['iDataRic'];
        /* $sql = "SELECT CURRENT_DATE()";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $row = $statement->fetch();
        if($dataRic <= $row) {
            throw new Exception("Data scelta già passata, scegli una data futura per la tua richiesta");
        }*/

        $tipoMobile = $_POST['iTipoMobile'];
        $fasciaOraria = $_POST['selFasciaOraria'];
        $provincia = $_POST['Provincia'];
        $regione = $_POST['selRegione'];
        $comune = $_POST['selComune'];
        $mess = $_POST['iMessRichiesta']; 
        $foto = $_POST['iFotoMobile']; 
        $linkMobile = $_POST['iLinkMobile'];
        $stato = 'inviata';

        $sql = "INSERT INTO Richiesta (TipoMobile, DataRichiesta, FasciaOraria, Utente, Comune, Provincia, Regione, MessaggioNote, FotoRichiesta, LinkRichiesta, StatoRichiesta)
                VALUES (:tipoMobile, :dataRic, :fasciaOraria, :user, :comune, :provincia, :regione, :mess, :foto, :linkMobile, :stato )";
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':tipoMobile', $tipoMobile);
        $statement->bindValue( ':dataRic', $dataRic);
        $statement->bindValue( ':fasciaOraria', $fasciaOraria);
        $statement->bindValue( ':user', $user);
        $statement->bindValue( ':comune', $comune);
        $statement->bindValue( ':provincia', $provincia);
        $statement->bindValue( ':regione', $regione);
        $statement->bindValue( ':mess', $mess);
        $statement->bindValue( ':foto', $foto);
        $statement->bindValue( ':linkMobile', $linkMobile);
        $statement->bindValue( ':stato', $stato);
        $statement->execute();
        
    }
    
    catch(PDOException | Exception $e) {
        $emess = $e->getMessage();
            $erroreinserimento = "C'è stato un errore nell'inserire la nuova richiesta </br>";
            echo $erroreinserimento ;
            echo $emess;
        }
    
    $connection->close();
    $pdo = null;

?>