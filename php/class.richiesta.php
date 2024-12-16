<?php


class Richiesta {
    public $id; //Richiesta(IDRichiesta)
    public $utente; //Utente(UserID)
    public $tipoMobile;
    public $data;
    public $fasciaOraria;//possibili valori: 8:00 - 10:00, 10:00 - 12:00, 12:00 - 14:00, 14:00 - 16:00, 16:00 - 18:00, 18:00 - 20:00
    public $note;
    public $link;
    public $indirizzo;
    public $comune;
    public $provincia;
    public $regione;
    public $stato; //possibili valori: inviata, presa in carico, conclusa, scaduta
    public $rispostaAccettata; //se non è ancora stata accettata nessuna risposta è NULL


    public function __construct($id) {
        $connection = new connectDB();
        $pdo = $connection->getPDO();
        $sql = "SELECT R.Utente,R.TipoMobile, R.DataRichiesta, R.FasciaOraria, R.MessaggioNote, R.LinkRichiesta,
                       R.Indirizzo, (SELECT C.Comune FROM Comune C WHERE C.id = R.Comune) AS Comune, R.Provincia, R.Regione, R.StatoRichiesta, R.RispostaAccettata   
                FROM Richiesta R 
                WHERE R.IDRichiesta = :id";

        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':id', $id);
        $statement->execute();
        $row = $statement->fetch();
        
        $this->id = $id;
        $this->utente = $row['Utente'];
        $this->tipoMobile = $row['TipoMobile'];
        $this->data = $row['DataRichiesta'];
        $this->fasciaOraria = $row['FasciaOraria'];
        $this->note = $row['MessaggioNote'];
        $this->link = $row['LinkRichiesta'];
        $this->indirizzo = $row['Indirizzo'];
        $this->comune = $row['Comune'];
        $this->provincia = $row['Provincia'];
        $this->regione = $row['Regione'];
        $this->stato = $row['StatoRichiesta'];
        $this->rispostaAccettata = $row['RispostaAccettata'];

        $connection->close();
        $pdo = null; 
    }
    
    //ritorna true se è stata recensita altrimenti false
    public function RichiestaRecensita() {
        $connection = new connectDB();
        $pdo = $connection->getPDO();
        $sql = "SELECT A.IDRecensione
                FROM Recensione A INNER JOIN Richiesta B ON A.RichiestaRecensita = B.IDRichiesta
                WHERE A.RichiestaRecensita = :id AND A.Recensore = :utente"; 
                
        $statement = $pdo->prepare($sql);
        $statement->bindValue( ':id', $this->id);
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

    //FUNZIONI UTILI PER OutputRichiestaArchivio
    //STAMPANO IL COMMMENTO IN BASE ALLO STATO DELLA RICHIESTA
    public function OutputRicPresaInCarico() {
        $connection = new connectDB();
        $pdo = $connection->getPDO();
        $sql1 = "SELECT I.Responsabile, I.Nome AS NomeImpresa, Risp.MessaggioRisposta
                 FROM Richiesta Ric INNER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                    INNER JOIN Impresa I ON I.Responsabile = Risp.Utente
                 WHERE Ric.IDRichiesta = :ric ";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue( ':ric', $this->id);
        $statement1->execute();
        $row1 = $statement1->fetch(); 
        echo '<div class="card">';
        echo    '<div class="container">';
        echo        '<p>La tua richiesta è stata presa in carico dal Titolare: '.$row1['Responsabile'].'</p>';
        echo        '<p><strong>dell\'Impresa</strong>: '.$row1['NomeImpresa'].'</p> ';
        //se c'è un messaggio associato alla risposta lo stampa
        if($row1['MessaggioRisposta']) {
            echo '<p><strong>con il messaggio</strong>: '.$row1['MessaggioRisposta'].'</p> ';
        }
        echo '<p><strong>Azioni successive: </strong>Passato l\'orario prestabilito potrai recensire il servizio!</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
        $connection->close();
        $pdo = null; 
    }

    public function OutputRicConclusa() {
        $connection = new connectDB();
        $pdo = $connection->getPDO();

        //recupero le informazioni dell'impresa la cui risposta è stata accettata
        $sql1 = "SELECT Ric.IDRichiesta, Risp.IDRisposta, I.Responsabile, I.Nome AS NomeImpresa, Risp.MessaggioRisposta
                 FROM Richiesta Ric INNER JOIN Risposta Risp ON Ric.RispostaAccettata = Risp.IDRisposta
                                    INNER JOIN Impresa I ON I.Responsabile = Risp.Utente
                 WHERE Ric.IDRichiesta = :ric ";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue( ':ric', $this->id);
        $statement1->execute();
        $row1 = $statement1->fetch();

        echo '<div class="card">';
        echo   '<div class="container">';
        echo     '<p>La tua richiesta è stata portata a termine dal Titolare '.$row1['Responsabile'].'</p>';
        echo     '<p><strong>dell\'Impresa</strong>: '.$row1['NomeImpresa'].'</p>'; 
        //se c'è un messaggio associato alla risposta lo stampa
        if($row1['MessaggioRisposta'] != ' ') {
            echo     '<p><strong>Messaggio dell\'impresa</strong>: '.$row1['MessaggioRisposta'].'</p>';
        }
        echo '</div></div><div class="separatore">&nbsp;</div>';

        if( $this->RichiestaRecensita() ) {
            $this->OutputRicConclusaRecensita();
        }
        else {
            $this->OutputRicConclusaNonRecensita($row1['NomeImpresa']);
        }

        $connection->close();
        $pdo = null;   
    }

    public function OutputRicConclusaRecensita() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p><strong>Recensione?</strong></p>';
        echo '<p>Hai già recensito il servizio ricevuto per questa richiesta. Per vedere tutte le recensioni fatte vai nella tua Area Personale.</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>'; 
    }

    public function OutputRicConclusaNonRecensita($nomeImpresa) {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p><strong>Come è stato il servizio offerto?</strong></p>';
        echo '<p><a href="./nuovaRecensione.php?ric='.$this->id.'" > clicca qui </a> per recensire ' . $nomeImpresa .'</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';  
    }

    public function OutputRicScaduta() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p><strong>Stato della richiesta</strong></p>';
        echo '<p>è passato l\'orario della tua richiesta senza nessuna risposta accettata.</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>'; 
    }

    public function OutputRicInviata() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p><strong>Stato della richiesta</strong></p>';
        echo '<p>non è ancora stata accettata nessuna risposta a questa richiesta, le possibili risposte puoi trovarle <a href="../php/LookForRisposte.php" >qui</a>.</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>'; 
    }

    //stampa a video le card con le informazioni sulla richiesta per l'archivio cliente
    public function OutputRichiestaArchivio() {

        echo   '<div class="card cardrichiesta">';
        echo   '<div class="container">'  ;
        echo   '<h4><b>Richiesta</b> #'. $this->id .'</h4> ';
        echo   '<p><strong>Tipo Mobile</strong>: '. $this->tipoMobile .'</p>';
        echo   '<p><strong>Data</strong>: '. $this->data .'&nbsp;&nbsp;&nbsp;<strong>Fascia Oraria</strong>: '. $this->fasciaOraria .'</p>';
        echo   '<p><strong>Localizzazione</strong>: '. $this->comune .'/'. $this->provincia .'/'. $this->regione .'</p>';
        echo   '<p><strong>Indirizzo: </strong>'. $this->indirizzo .'</p>';
        if( $this->note != ' ' ) {
            echo   '<p><strong>Messaggio: </strong>'. $this->note .'</p>';
        }
        echo   '<p><strong>Stato della richiesta</strong>: '. $this->stato .'</p>';
        echo   ' </div></div><div class="separatore">&nbsp;</div>'; 
            
        //differenziazione in base allo stato della richiesta
        switch( $this->stato ){
            case 'conclusa':
                $this->OutputRicConclusa();
            break;
            case 'scaduta':
                $this->OutputRicScaduta();
            break;
            case ' presa in carico':
                $this->OutputRicPresaInCarico();
            break;
            case 'inviata':
                $this->OutputRicInviata();
            break;
        }

        echo '<div class="separatore">&nbsp;</div><div id="divRisposte'. $this->id .'"></div>';
    } 
};

// -----------------------------------------------------------------------------------------------------------
class RichiesteUtente {
    public $userID;
    public $richieste; //array di elementi Richiesta: tutte le richieste fatte dall'utente

    public function __construct($userID) {
        $this->userID = $userID;

        $connection = new connectDB();
        $pdo = $connection->getPDO();

        $sql = "SELECT R.IDRichiesta
                FROM Richiesta R
                WHERE R.Utente = :utente 
                ORDER BY R.DataRichiesta DESC";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':utente', $userID);
        $statement->execute();
        
        $richieste = [];
        while ($row = $statement->fetch()) {
            $richieste[] = new Richiesta($row['IDRichiesta']);
        }
        $this->richieste = $richieste;
        $connection->close();
        $pdo = null;          
    }

    public function OutputRichiesteUtenteArchivio() {
        if( !$this->richieste ) {
            echo "non hai effettuato richieste";
        }
        else {
            for( $i = 0 ; $i < count($this->richieste) ; $i++) {
                $this->richieste[$i]->OutputRichiestaArchivio();
            }
        }

    }
}

// -----------------------------------------------------------------------------------------------------------
class RichiesteArea {
    public $user; //per non mostrare le richieste a cui l'utente ha già risposto
    public $areatype; //può essere 'Regione', 'Provincia' o 'Comune'
    public $area;
    public $richieste; //array di elementi Richiesta: tutte le richieste fatte nell'area scelta

    public function __construct($areatype, $user) {
        $this->areatype = $areatype;
        $this->user = $user;        
        $area = getArea($user, $areatype);//utlityFunctions
        $this->area = $area;

        $richieste = [];

        switch($areatype) {
            case 'Regione':
                $richieste = getRichiesteRegione($area, $user); //utlityFunctions
            break;
            case 'Provincia':
                $richieste = getRichiesteProvincia($area, $user);//utlityFunctions
            break;
            case 'Comune':
                $richieste = getRichiesteComune($area, $user);//utlityFunctions
            break;
        }

        $this->richieste = $richieste;   
    }
}
?>