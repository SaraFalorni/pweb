<?php

    class Utente {
        public $userID; // Utente(UserID)
        public $nome;
        public $cognome;
        public $email;
        public $pwd;
        public $dataNascita;
        public $impresa; //oggetto di tipo impresa, se l'utente è registrato come cliente è null
        
        public function __construct($currentuser) {
            $connection = new connectDB();
            $pdo = $connection->getPDO();

            $sql = "SELECT U.UserID, U.Nome, U.Cognome, U.Email, U.Pwd, U.DataNascita, I.IDImpresa
                    FROM Utente U LEFT OUTER JOIN Impresa I ON U.UserID = I.Responsabile
                    WHERE U.UserID = :currentuser";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':currentuser', $currentuser);
            $statement->execute();
            $row = $statement->fetch();

            $this->userID = $row['UserID'];
            $this->nome = $row['Nome'];
            $this->cognome = $row['Cognome'];
            $this->email = $row['Email'];
            $this->pwd = $row['Pwd'];
            $this->dataNascita = $row['DataNascita'];

            if( $row['IDImpresa'] ) {
                $this->impresa = new Impresa($row['IDImpresa']);
            }
            else {
                $this->impresa = null;
            }

            $connection->close();
            $pdo = null; 
        }

        public function mostraModificaInfo() {

            echo 'UserID <br/> <input type="text" class="UserInput" name="UserID" value="'. $this->userID .'" readonly> <br/>';
            echo 'Nome <br/> <input type="text" class="UserInput" name="nome" value="'. $this->nome .'"> <br/>';
            echo 'Cognome <br/> <input type="text" class="UserInput" name="cognome" value="'. $this->cognome .'"> <br/>';
            echo 'email <br/> <input type="text" class="UserInput" name="email" value="'. $this->email .'"> <br/>';
            echo 'password <br/> <input type="password" class="UserInput" id="pwd" name="pwd" > <br/>';
            echo 'conferma password <br/> <input type="password" class="UserInput" id="cpwd" name="cpwd" onblur="checkPasswordString()" >';
            echo '<label id="errorPwd" style="display:none;">le password inserite differiscono</label> <br/>';
            echo 'Data Di Nascita <br/> <input type="date" class="UserInput" id="birthDate" name="birthDate" onblur="checkBirthDate()" value="'. $this->dataNascita .'">';
            echo '<label id="errorBirthDate" style="display:none;">Data di Nascita non accettabile</label> <br/>';
        }
    }

    class Impresa {
        public $id; // Impresa(IDImpresa)
        public $nomeImpresa;
        public $descrizione;
        public $responsabile; //Utente(UserID)
        public $comune;
        public $provincia;
        public $regione;

        public function __construct($idImpresa) {
            $connection = new connectDB();
            $pdo = $connection->getPDO();
            
            $sql = "SELECT I.IDImpresa, I.Nome, I.Descrizione, I.Responsabile, C.Regione, C.Provincia, C.Comune
                    FROM Impresa I INNER JOIN Comune C ON I.Comune = C.id
                    WHERE I.IDImpresa = :idImpresa";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':idImpresa', $idImpresa);
            $statement->execute();
            $row = $statement->fetch();

            $this->id = $row['IDImpresa'];
            $this->nomeImpresa = $row['Nome'];
            $this->descrizione = $row['Descrizione'];
            $this->responsabile = $row['Responsabile'];
            $this->comune = $row['Comune'];
            $this->provincia = $row['Provincia'];
            $this->regione = $row['Regione'];

            $connection->close();
            $pdo = null; 
        } 

        public function mostraModificaInfo() {
            echo  '<div name="Impresa" id="Impresa" >';
            echo 'Nome dell\'Impresa <br/> <input type="text" class="UserInput" name = "nomeImpresa" value="'. $this->nomeImpresa .'"> <br/>';

            echo '<label for="Regione">Regione</label>';
            echo '<select name="Regione" id="Regione" onclick = "emptyOptionsProvincia();">';
            echo '<option disabled selected value> '. $this->regione .' </option>';
            echo '<option value="Abruzzo">Abruzzo</option>';
            echo '<option value="Basilicata">Basilicata</option>';
            echo '<option value="Calabria">Calabria</option>';
            echo '<option value="Campania">Campania</option>';
            echo '<option value="Emilia Romagna">Emilia Romagna</option>';
            echo '<option value="Friuli Venezia Giulia">Friuli Venezia Giulia</option>';
            echo '<option value="Lazio">Lazio</option>';
            echo '<option value="Liguria">Liguria</option>';
            echo '<option value="Lombardia">Lombardia</option>';
            echo '<option value="Marche">Marche</option>';
            echo '<option value="Molise">Molise</option>';
            echo '<option value="Piemonte">Piemonte</option>';
            echo '<option value="Puglia">Puglia</option>';
            echo '<option value="Sardegna">Sardegna</option>';
            echo '<option value="Sicilia">Sicilia</option>';
            echo '<option value="Toscana">Toscana</option>';
            echo '<option value="Trentino Alto Adige">Trentino Alto Adige</option>';
            echo '<option value="Umbria">Umbria</option>';
            echo '<option value="Val D’Aosta">Val D’Aosta</option>';
            echo '<option value="Veneto">Veneto</option>';
            echo '</select> &nbsp; &nbsp; ';

            echo '<label for="Provincia">Provincia</label>';
            echo '<select name="Provincia" id="Provincia" onfocus="findProvincia(\'Regione\');" >';
            echo '<option disabled selected value> '. $this->provincia .' </option>';     
            echo '</select> <br/> <br/>';

            echo '<label for="Comune">Comune</label>';
            echo '<select name="Comune" id="Comune" onfocus="LoadComuni(\'Comune\');" >';
            echo '<option disabled selected value> '. $this->comune .' </option>';
            echo '</select> <br/>';                              
            echo 'Descrizione <br/> <textarea type="textarea" class="UserInput" id="bio" name="bio"></textarea> <br/>';
            echo '</div> <br/> <br/>';
        }

    }

?>