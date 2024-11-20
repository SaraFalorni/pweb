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


    function __construct($id, $utente, $tipoMobile, $data, $fasciaOraria, $note, $link, $indirizzo, $comune, $provincia, $regione, $stato) {
        $this->id = $id;
        $this->utente = $utente;
        $this->tipoMobile = $tipoMobile;
        $this->data = $data;
        $this->fasciaOraria = $fasciaOraria;
        $this->note = $note;
        $this->link = $link;
        $this->indirizzo = $indirizzo;
        $this->comune = $comune;
        $this->provincia = $provincia;
        $this->regione = $regione;
        $this->stato = $stato;
    }
    
    //ritorna true se è stata recensita altrimenti false
    public function RichiestaRecensita() {
        $sql = "SELECT A.IDRecensione
                FROM Recensione A INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta
                WHERE A.Richiesta = :id AND A.Recensito = :utente"; 
                
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
    }

    //FUNZIONI UTILI PER OutputRichiestaArchivio
    //STAMPANO IL COMMMENTO IN BASE ALLO STATO DELLA RICHIESTA
    public function OutputRicPresaInCarico() {
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
    }

    public function OutputRicConclusa() {
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
        if($row1['MessaggioRisposta']) {
            echo     '<p><strong>Messaggio dell\'impresa</strong>: '.$row1['MessaggioRisposta'].'</p>';
        }
        echo '</div></div><div class="separatore">&nbsp;</div>';

        if($this.RichiestaRecensita()) {
            $this.OutputRicConclusaRecensita();
        }
        else {
            $this.OutputRicConclusaNonRecensita($row1['NomeImpresa']);
        }
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

    //stampa a video le card con le informazioni sulla richiesta per l'archivio cliente
    public function OutputRichiestaArchivio() {

        echo   '<div class="card cardrichiesta">';
        echo   '<div class="container">'  ;
        echo   '<h4><b>Richiesta</b> #'. $this->id .'</h4> ';
        echo   '<p><strong>Tipo Mobile</strong>: '. $this->tipoMobile .'</p>';
        echo   '<p><strong>Data</strong>: '. $this->data .'&nbsp;&nbsp;&nbsp;<strong>Fascia Oraria</strong>: '. $this->fasciaOraria .'</p>';
        echo   '<p><strong>Localizzazione</strong>: '. $this->comune .'/'. $this->provincia .'/'. $this->regione .'</p>'
        echo   '<p><strong>Indirizzo: </strong>'. $this->indirizzo .'</p>';
        if( $this->note ) {
            echo   '<p><strong>Messaggio: </strong>'. $this->note .'</p>';
        }
        echo   '<p><strong>Stato della richiesta</strong>: '. $this->stato .'</p>';
        echo   ' </div></div><div class="separatore">&nbsp;</div>'; 
            
        //differenziazione in base allo stato della richiesta
        switch( $this->stato ){
            case 'conclusa':
                $this.OutputRicConclusa();
            break;
            case 'scaduta':
                $this.OutputRicScaduta();
            break;
            case ' presa in carico':
                $this.OutputRicPresaInCarico();
            break;
            //nel caso di richiesta inviata non viene stampato nient'altro
        }

        echo '<div class="separatore">&nbsp;</div><div id="divRisposte'. $this->id .'"></div>';
    }

    //stampa a video la card con le informazioni sulla richiesta per la LookForRequest.php (getRequest.php) dell'impresa
    public function OutputRichiestaCerca() {
        
        echo '<div class="richiestaincerca" id="richiesta'. $this->id .'">';
        echo '<p>Richiesta <b>#'. $this->id .'</b> di <b>' . $this->utente . '</b></p>';
        echo '<p>per il montaggio di: <b>'.  $this->tipoMobile  . '</b></p>';
        echo '<p>nel comune di: <b>'.  $this->comune  . '</b></p>';
        echo '<p>nella fascia oraria: <b>'.  $this->fasciaOraria  . '</b></p>';
        if( $this->note ) {
            echo '<p>' .  $this->utente  . ' scrive: <b>'.  $this->note  . '</b></p>';
        }            

        if( $this->link ) {
            echo '<p><a href="'. $this->link . '">link al mobile </a> </p>';
        }

        echo '<p>Rispondi alla richiesta!&nbsp;<input type="button" id="btnrispondi" onclick="openFormRisp('
            .  $this->id  .', \''. $user .'\' )" class="btn btn-smaller" value="Rispondi"></input> </p>' ; 
        echo '<input type="hidden" id= "iIDRichiesta" name= "iIDRichiesta'. $this->id .'" value="'.  $this->id  . '" > </input>' ; 
        echo '<input type="hidden" id= "iIDUser" name= "iIDUser'.$user.'" value="'. $user . '" > </input>' .'</div>';
        echo '<p><a href="checkRecensioni.php?utente='. $this->utente .'">Clicca qui</a> per leggere le recensioni ricevute da '. $this->utente ;
        echo '</p> <div class="separatore">&nbsp;</div>';
    }
    
    
};

class Risposta {
    public $id; //Risposta(IDRisposta)
    public $utente; //Utente(UserID)
    public $richiesta; // oggetto di tipo Richiesta (contiene già tutte le info relative alla richiesta)
    public $note;
    public $stato; //possibili valori: inviata, accettata, rifiutata, scaduta

    __construct($id, $utente, $richiesta, $note, $stato) {
        $this->id = $id;
        $this->utente = $utente;
        $this->$richiesta = $richiesta;
        $this->note = $note;
        $this->$stato = $stato;
    }
 
    //ritorna true se è stata recensita altrimenti false
    public function RispostaRecensita() {
        $sql = "SELECT A.IDRecensione
        FROM Recensione A INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta
        WHERE A.Richiesta = :idRic AND A.Recensito = :utente AND A.RispostaAccettata = :id"; 
        
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
    }

    //FUNZIONI UTILI PER OutputRispostaArchivio
    //STAMPANO IL COMMMENTI IN BASE ALLO STATO DELLA RISPOSTA
    public function OutputRispInviata() {
        echo '<div class="card">';
        echo '<div class="container">';
        echo '<p>La tua risposta non è stata ancora visionata dal cliente, verrai notificato quando questo accade.</p>';
        echo '</div></div><div class="separatore">&nbsp;</div>';
    }

    public function OutputRispAccettata() {
        if( $this->richiesta->stato == "conclusa") {
            if( $this.RispostaRecensita() ) {
                $this.OutputRispRicConclusaRecensita();
            }
            else {
                $this.OutputRispRicConclusaNonRecensita();
            }
        }
        else { //è accettata ma non ancora conclusa
            //richiesta a cui si riferisce non si è ancora conclusa
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
        }
    }

    public function OutputRispRicConclusaRecensita() {
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
        //??????????
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
                $this.OutputRispAccettata();
            break;
            case 'inviata':
                $this.OutputRispInviata();
            break;
            case 'rifiutata':
                $this.OutputRispRifiutata();
            break;
            case 'scaduta':
                $this.OutputRispScaduta();
            break;
        }
    }

    //stampa a video la card con le informazioni sulla risposta per la LookForRisposte.php (getRisposte.php) del cliente
    public function OutputRispostaCerca() {
        $sql = "SELECT impr.NomeImpresa, impr.Descrizione,impr.Comune,impr.CognomeResponsabile,impr.NomeResponsabile,impr.ComuneNome, impr.Provincia, impr.Regione
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
        echo '<input class=\'btn\' type = "button" id="bAccetta'. $this->richiesta->id . '" value="Accetta" onclick="ChangeRispostaStatus( \'accettata\', '. $this->richiesta->id . ' );HideButtons('. $this->richiesta->id .');"></input>&nbsp;' ;
        '<input class=\'btn\' type = "button" id="bRifiuta'. $this->richiesta->id . '" value="Rifiuta" onclick="ChangeRispostaStatus(\'rifiutata\', '. $this->richiesta->id . ' );HideButtons('. $this->richiesta->id .');"></input>' . 
        '<input class=\'btn\' type="hidden" id="IHRisposta'. $this->richiesta->id . '" name="IHRisposta'. $this->richiesta->id . '" value = "'.$this->id.'" > </input> '. 
        '<p>Clicca <a href="checkRecensioni.php?utente='. $this->utente .'">qui </a> per leggere le recensioni ricevute da '. $row['NomeResponsabile'] .' '. $row['CognomeResponsabile'] . '</p> </div>' ;

    }
}

class Recensione {
    public $id; // Recensione(IDRecensione)
    public $richiesta; //oggetto di tipo Richiesta 
    public $risposta; //oggetto di tipo Risposta
    public $recensito; //Utente(UserID)
    public $recensore; //Utente(UserID)
    public $testo;
    public $rating;

    function __construct($id,$richiesta, $risposta, $recensito, $recensore, $testo, $rating) {
        $this->id = $id;
        $this->richiesta = $richiesta;
        $this->risposta = $risposta;
        $this->recensito = $recensito;
        $this->recensore = $recensore;
        $this->testo = $testo;
        $this->rating = $rating;
    }
    //funzioni che stampano le card delle recensioni in areaPersonale.php
    //simili tra loro ma variano in dettagli in base al tipo di recensore e recensito

    public function OutputRecensioneRicevuta($usertype) {
        echo '<div id="divRecensione'.  $this->id .'" class="card">';
        echo '<div class="container">';
        echo '<h4> Recensione #'. $this->id .'</h4>';
        if( $usertype == "cliente") {
            echo '<p><strong>Impresa: </strong>'. $this->recensore .'</p>';
        }
        else {
            echo '<p><strong>Servizio offerto per: </strong>'. $this->recensore .'</p>';
        }
        echo '<p><strong>Tipo di mobile: </strong>'. $this->richiesta->tipoMobile .'</p>';
        echo '<p><strong>Rating: </strong>'. $this->rating .'</p>';
        echo '<p><strong>Testo: </strong>'. $this->testo .'</p>';
        .'</div></div><div class="separatore"></div>';
    }

    public function OutputRecensioneFatta($usertype) {
        echo '<div id="divRecensione'. $this->id .'" class="card">';
        echo '<div class="container">';
        echo '<h4> Recensione #'. $this->id .'</h4>';
        if( $usertype == "cliente") {
            echo '<p><strong>Servizio offerto da: </strong>'. $this->recensito .'</p>';
        }
        else {
            echo '<p><strong>Cliente: </strong>'. $this->recensito .'</p>';
        }
        echo '<p><strong>Tipo di mobile: </strong>'. $this->richiesta->tipoMobile .'</p>';
        echo '<p><strong>Rating: </strong>'. $this->rating .'</p>';
        echo '<p><strong>Testo: </strong>'. $this->testo .'</p>';
        echo '</div></div><div class="separatore"></div>';
    }

    //usata in checkRecensioni.php
    public function OutputRecensioneUtente() {
        echo '<div id="divRecensione'.$this->id .'" class="card">';
        echo '<div class="container">'; 
        echo '<h4> Recensione #'.$this->id .'</h4>';
        echo '<p><strong>Recensore: </strong>'.$this->recensore .'</p>';
        echo '<p><strong>Tipo di mobile: </strong>'.$this->richiesta->tipoMobile.'</p>';
        echo '<p><strong>Rating: </strong>'.$this->rating .'</p>';
        echo '<p><strong>Testo: </strong>'.$this->testo .'</p>';
        echo '</div></div><div class="separatore"></div>';
    }
}

?>