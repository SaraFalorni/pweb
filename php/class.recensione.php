<?php
class Recensione {
    public $id; // Recensione(IDRecensione)
    public $richiesta; //oggetto di tipo Richiesta 
    public $risposta; //oggetto di tipo Risposta
    public $recensito; //Utente(UserID)
    public $recensore; //Utente(UserID)
    public $testo;
    public $rating;

    public function __construct($id,$richiesta, $risposta, $recensito, $recensore, $testo, $rating) {
        $this->id = $id;
        $this->richiesta = new Richiesta($richiesta);
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
        echo '</div></div><div class="separatore"></div>';
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
// -----------------------------------------------------------------------------------------------------------
function getRecensioniFatte($currentuser, $usertype) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    $sql = "SELECT A.IDRecensione, A.RichiestaRecensita, B.RispostaAccettata, A.Recensito, A.Recensore, A.TestoRecensione, A.Rating
            FROM Recensione A LEFT OUTER JOIN Richiesta B ON A.RichiestaRecensita = B.IDRichiesta
            WHERE A.Recensore = :user";
    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':user', $currentuser);
    $statement->execute();
    $recensioni = [];
    while($row = $statement->fetch()) {
        $recensioni[] = new Recensione($row['IDRecensione'],$row['RichiestaRecensita'],$row['RispostaAccettata'],$row['Recensito'],$row['Recensore'],$row['TestoRecensione'],$row['Rating']);
    }
    if( !$recensioni ) {
        echo "Non hai ancora scritto nessuna recensione.";
    }
    else {
        for( $i = 0 ; $i < count($recensioni) ; $i++) {
            $recensioni[$i]->OutputRecensioneFatta($usertype);
        }
    }
    $connection->close();
    $pdo = null; 
}


function getRecensioniRicevute($currentuser, $usertype) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    $sql = "SELECT A.IDRecensione, A.RichiestaRecensita, B.RispostaAccettata, A.Recensito, A.Recensore, A.TestoRecensione, A.Rating
            FROM Recensione A LEFT OUTER JOIN Richiesta B ON A.RichiestaRecensita = B.IDRichiesta
                              LEFT OUTER JOIN Risposta C ON B.RispostaAccettata = C.IDRisposta
            WHERE A.Recensito = :user";
    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':user', $currentuser);
    $statement->execute();
    $recensioni = [];
    while($row = $statement->fetch()) {
        $recensioni[] = new Recensione($row['IDRecensione'],$row['RichiestaRecensita'],$row['RispostaAccettata'],$row['Recensito'],$row['Recensore'],$row['TestoRecensione'],$row['Rating']);
    }
    if( !$recensioni ) {
        echo "Non hai ancora ricevuto nessuna recensione.";
    }
    else {
        for( $i = 0 ; $i < count($recensioni) ; $i++) {
            $recensioni[$i]->OutputRecensioneRicevuta($usertype);
        }
    } 
    $connection->close();
    $pdo = null;    
}
?>