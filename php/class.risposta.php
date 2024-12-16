<?php
class Risposta {
    public $id; //Risposta(IDRisposta)
    public $utente; //Utente(UserID)
    public $richiesta; // oggetto di tipo Richiesta (contiene già tutte le info relative alla richiesta)
    public $note;
    public $stato; //possibili valori: inviata, accettata, rifiutata, scaduta

   public function __construct($id, $utente, $richiesta, $note, $stato) {
        $this->id = $id;
        $this->utente = $utente;
        $this->richiesta = new Richiesta($richiesta->id);
        $this->note = $note;
        $this->stato = $stato;
    }

    
 
    //ritorna true se è stata recensita altrimenti false
    public function RispostaRecensita() {
        $connection = new connectDB();
        $pdo = $connection->getPDO();

        $sql = "SELECT A.IDRecensione
                FROM Recensione A INNER JOIN Richiesta B ON A.RichiestaRecensita = B.IDRichiesta
                WHERE A.RichiestaRecensita = :idRic AND A.Recensore = :utente AND B.RispostaAccettata = :id"; 
        
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':id', $this->id);
        $statement->bindValue( ':idRic', $this->richiesta->id);
        $statement->bindValue( ':utente', $this->utente);
        $statement->execute();
        $row = $statement->fetch();

        if($row) {
            //esiste il record: è gia stata recensita
            return true;
        }
        else {
            //non esiste il record: non è ancora stata recensita
            return false;
        }
        $connection->close();
        $pdo = null;   
    }

    //FUNZIONI UTILI PER OutputRispostaArchivio
    //STAMPANO IL COMMMENTI IN BASE ALLO STATO DELLA RISPOSTA
    public function OutputRispInviata() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p>La tua risposta non è stata ancora visionata dal cliente, verrai notificato quando questo accade.</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
    }

    public function OutputRispConclusa() {
        if( $this->RispostaRecensita() ) {
            $this->OutputRispConclusaRecensita();
        }
        else {
            $this->OutputRispConclusaNonRecensita();
        }
    }

    public function OutputRispAccettata() {
        $connection = new connectDB();
        $pdo = $connection->getPDO();

        $sql2 = "SELECT B.Email
        FROM Richiesta A INNER JOIN Utente B ON A.Utente = B.UserID 
        WHERE A.IDRichiesta = :ric ";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue( ':ric', $this->richiesta->id );
        $statement2->execute();
        $row2 = $statement2->fetch(); 
        echo '<div class="card">';
        echo '<div class="container" style="border : solid 4px red; background-color:rgba(255,0,0,0.3)">';
        echo '<p>Ricordati di presentarti il giorno '. $this->richiesta->data .' alle '. $this->richiesta->fasciaOraria . ' all\'indirizzo ' . $this->richiesta->indirizzo . ' nel comune di '. $this->richiesta->comune . '</p>';
        echo '<p>Puoi contattare il cliente via email all\'indirizzo '. $row2['Email'] . ' </p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';

        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p>La tua risposta è stata accettata dal cliente, passato l\'orario prestabilito potrai recensire il cliente!</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
    
        $connection->close();
        $pdo = null;   
    }

    public function OutputRispConclusaRecensita() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p>Hai già recensito il cliente per questo servizio. Per vedere tutte le recensioni fatte vai nella tua Area Personale.</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>'; 
   }

   public function OutputRispConclusaNonRecensita() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p> Come è stato il offrire il servizio al cliente? <a href="./nuovaRecensione.php?ric='. $this->richiesta->id .'" > clicca qui </a> per recensire ' . $this->richiesta->utente .' </p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
   }

    public function OutputRispRifiutata() {
        
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p>La tua risposta è stata rifiutata dal cliente.';
        echo 'Per cercare altre richieste a cui proporre il tuo servizio clicca <a href="./lookForRequest.php" >qui</a>!</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
    }

    public function OutputRispScaduta() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p>La tua risposta è scaduta.';
        echo 'Per cercare altre richieste a cui proporre il tuo servizio clicca <a href="./lookForRequest.php" >qui</a>!</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
    }   

    //stampa a video le card con le informazioni sulla risposta per l'archivio impresa
    public function OutputRispostaArchivio() {
        echo '<div class="card cardrisposta">';
        echo   '<div class="container">';
        echo     '<h4><b>Richiesta</b> #'. $this->richiesta->id .'</h4>'; 
        echo     '<p><strong>Tipo Mobile</strong>: '. $this->richiesta->tipoMobile .'</p>'; 
        echo     '<p><strong>Data</strong>: '. $this->richiesta->data .'&nbsp;&nbsp;&nbsp;<strong>Fascia Oraria</strong>: '. $this->richiesta->fasciaOraria .'</p>';
        echo     '<p><strong>Localizzazione</strong>: '. $this->richiesta->comune .'/'. $this->richiesta->provincia .'/'. $this->richiesta->regione .'</p>';
        echo     '<p><strong>Utente</strong>: '. $this->richiesta->utente.'</p>';
        //se c'è un messaggio associato alla richiesta
        if( $this->richiesta->note ) {
            echo   '<p><strong>Messaggio della richiesta: </strong>'. $this->richiesta->note .'</p>';
        }
        echo     '<p><strong>Stato della risposta</strong>: '. $this->stato .'</p>';
        //se c'è un messaggio associato alla risposta
        if( $this->note ) {
            echo   '<p><strong>Messaggio della risposta: </strong>'. $this->note .'</p>';
        }
        echo     '</div></div><div class="separatore">&nbsp;</div>';

        //commenti in base allo stato della risposta
        switch($this->stato) {
            case 'accettata':
                $this->OutputRispAccettata();
            break;
            case 'inviata':
                $this->OutputRispInviata();
            break;
            case 'rifiutata':
                $this->OutputRispRifiutata();
            break;
            case 'scaduta':
                $this->OutputRispScaduta();
            break;
            case 'conclusa':
                $this->OutputRispConclusa();
            break;
        }
    }

    //stampa a video la card con le informazioni sulla risposta per la LookForRisposte.php (getRisposte.php) del cliente
    public function OutputRispostaCerca() {
        $connection = new connectDB();
        $pdo = $connection->getPDO();

        $sql = "SELECT impr.Nome AS NomeImpresa, impr.Descrizione,impr.Comune,impr.CognomeResponsabile,impr.NomeResponsabile,impr.ComuneNome, impr.Provincia, impr.Regione
                FROM Risposta A INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta 
                                LEFT JOIN (SELECT i.*, u.Nome AS NomeResponsabile, u.Cognome AS CognomeResponsabile, 
                                                  c.Comune AS ComuneNome, c.Provincia, c.Regione
                                           FROM impresa i INNER JOIN utente u ON i.responsabile=u.userid
                                                          INNER JOIN Comune c ON i.Comune=c.id
                                          ) AS impr
                                ON A.utente = impr.Responsabile
                WHERE Richiesta = :richiesta AND StatoRisposta <> 'rifiutata' ";
            
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':richiesta', $this->richiesta->id);
        $statement->execute();
        $row = $statement->fetch();
        echo ' <div id="divRisposta'. $this->id .'">';
        echo '<h4><b>Risposta</b> #'. $this->id .'</h4> ';
        echo '<p><strong>Impresa</strong>: '.$row['NomeImpresa'].'</p>';
        echo '<p><strong>Referente</strong>: '.$row['CognomeResponsabile'].', '.$row['NomeResponsabile'].'</p>';
        echo '<p><strong>Descrizione</strong>: '.$row['Descrizione'].'</p>';
        echo '<p><strong>Localizzazione</strong>: '.$row['ComuneNome'].'/'.$row['Provincia'].'/'.$row['Regione'].'</p>';
        if( $this->note ) {
            echo '<p><strong>Messaggio per te</strong>: '. $this->note .'</p>';
        }  
        echo '<p>Clicca <a href="checkRecensioni.php?utente='. $this->utente .'">qui </a> per leggere le recensioni ricevute da '. $row['NomeResponsabile'] .' '. $row['CognomeResponsabile'] . '</p> ' ;
        echo '<input class=\'btn\' type = "button" id="bAccetta'. $this->richiesta->id . '" value="Accetta" onclick="ChangeRispostaStatus( \'accettata\', '. $this->richiesta->id . ' );HideButtons('. $this->richiesta->id .');"></input>&nbsp;' ;
        echo '<input class=\'btn\' type = "button" id="bRifiuta'. $this->richiesta->id . '" value="Rifiuta" onclick="ChangeRispostaStatus(\'rifiutata\', '. $this->richiesta->id . ' );HideButtons('. $this->richiesta->id .');"></input>' ;
        echo '<input class=\'btn\' type="hidden" id="IHRisposta'. $this->richiesta->id . '" name="IHRisposta'. $this->richiesta->id . '" value = "'.$this->id.'" > </input> </div>';
        

        $connection->close();
        $pdo = null;   
    }
}
// -----------------------------------------------------------------------------------------------------------
class RisposteUtente {
    public $userID;
    public $risposte; //array di elementi Risposta: tutte le risposte fatte dall'utente

    public function  __construct($userID) {
        $this->userID = $userID;

        $connection = new connectDB();
        $pdo = $connection->getPDO();

        $sql = "SELECT A.IDRisposta, A.Utente AS UtenteRisposta, A.MessaggioRisposta, A.StatoRisposta,
                       R.IDRichiesta, R.Utente AS UtenteRichiesta, R.TipoMobile, R.DataRichiesta, R.FasciaOraria, R.MessaggioNote, R.LinkRichiesta, R.Indirizzo, R.StatoRichiesta,
                       Regione, Provincia, (SELECT C.Comune FROM Comune C WHERE C.id = R.Comune) AS Comune
                FROM Risposta A INNER JOIN Richiesta R ON A.Richiesta = R.IDRichiesta
                WHERE A.Utente = :utente 
                ORDER BY R.DataRichiesta DESC";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':utente', $userID);
        $statement->execute();
        
        $risposte = [];
        while ($row = $statement->fetch()) {
            $ric = new Richiesta($row['IDRichiesta']);  
            $risposte[] = new Risposta($row['IDRisposta'], $row['UtenteRisposta'], $ric, $row['MessaggioRisposta'],$row['StatoRisposta'] );
        }
        $this->risposte = $risposte;   
        $connection->close();
        $pdo = null;          
    }

    public function OutputRisposteUtenteArchivio() {
        if( !$this->risposte ) {
            echo "non hai effettuato risposte";
        }
        else {
            for( $i = 0 ; $i < count($this->risposte) ; $i++) {
                $this->risposte[$i]->OutputRispostaArchivio();
            }
        }
    }
}

// -----------------------------------------------------------------------------------------------------------
class RispostePerRichiesta {
    public $richiesta;
    public $risposte; //array di elementi Risposta: tutte le risposte per la richiesta 

    public function  __construct($richiesta) {
        $this->richiesta = $richiesta;

        $connection = new connectDB();
        $pdo = $connection->getPDO();

        $sql = "SELECT R.IDRisposta, R.Richiesta, R.Utente, R.MessaggioRisposta, R.StatoRisposta
                FROM Risposta R
                WHERE R.Richiesta = :richiesta";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':richiesta', $richiesta);
        $statement->execute();
        
        $risposte = [];
        while ($row = $statement->fetch()) {
            $ric = new Richiesta($row['Richiesta']);  
            $risposte[] = new Risposta($row['IDRisposta'], $row['Utente'], $ric, $row['MessaggioRisposta'],$row['StatoRisposta'] );
        }
        $this->risposte = $risposte;   
        $connection->close();
        $pdo = null;          
    }

    public function OutputRispostePerRichiesteCerca() {
        if( !$this->risposte ) {
            echo "non ci sono risposte a questa richiesta";
        }
        else {
            for( $i = 0 ; $i < count($this->risposte) ; $i++) {
                $this->risposte[$i]->OutputRispostaCerca();
            }
        }
    }
}
?>