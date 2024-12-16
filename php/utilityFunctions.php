<?php

function getDatiPersonaliCliente($currentuser) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();
    $sql = "SELECT A.UserID, A.Nome, A.Cognome, 
                IFNULL(B.RichiesteFatte,0) AS RichiesteFatte , IFNULL(C.RisposteRicevute,0) AS RisposteRicevute, 
                IFNULL(D.RisposteAccettate,0) AS RisposteAccettate, IFNULL(E.RecensioniFatte,0) AS RecensioniFatte,
                IFNULL(F.RecensioniRicevute,0) AS RecensioniRicevute, IFNULL(G.RatingMedio,0) AS RatingMedio
            FROM Utente A
                    LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RichiesteFatte
                                    FROM Richiesta
                                    GROUP BY Utente
                                    ) B ON A.UserID = B.Utente
                    LEFT OUTER JOIN (SELECT B.Utente, COUNT(*) AS RisposteRicevute
                                    FROM Risposta A 
                                    INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta
                                    GROUP BY B.Utente
                                    ) C ON A.UserID = C.Utente
                    LEFT OUTER JOIN (SELECT B.Utente, COUNT(*) AS RisposteAccettate
                                    FROM Risposta A 
                                    INNER JOIN Richiesta B ON A.Richiesta = B.IDRichiesta
                                    WHERE A.StatoRisposta = 'accettata'
                                    GROUP BY B.Utente
                                    ) D ON A.UserID = D.Utente
                    LEFT OUTER JOIN (SELECT Recensore, COUNT(*) AS RecensioniFatte
                                    FROM Recensione
                                    GROUP BY Recensore
                                    ) E ON A.UserID = E.Recensore
                    LEFT OUTER JOIN (SELECT Recensito, COUNT(*) AS RecensioniRicevute
                                    FROM Recensione
                                    GROUP BY Recensito
                                    ) F ON A.UserID = F.Recensito
                    LEFT OUTER JOIN (SELECT Recensito, AVG(Rating) AS RatingMedio
                                    FROM Recensione
                                    GROUP BY Recensito
                                    ) G ON A.UserID = G.Recensito
                WHERE A.UserID = :user;";
    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':user', $currentuser);
    $statement->execute();
    $row = $statement->fetch();

    echo '<p><strong>Nome:  </strong>'.$row['Nome'].'</p>';
    echo '<p><strong>Cognome: </strong>'.$row['Cognome'].'</p>';
    echo '<p><strong>Richieste Fatte: </strong>'.$row['RichiesteFatte'].'</p>';
    echo '<p><strong>Risposte Ricevute: </strong>'.$row['RisposteRicevute'].'</p>';
    echo '<p><strong>Risposte Accettate: </strong>'.$row['RisposteAccettate'].'</p>';
    echo '<p><strong>Recensioni Fatte: </strong>'.$row['RecensioniFatte'].'</p>';
    echo '<p><strong>Recensioni Ricevute: </strong>'.$row['RecensioniRicevute'].'</p>';
    echo '<p><strong>Rating Medio: </strong>'.$row['RatingMedio'].'</p>';
    
    $connection->close();
    $pdo = null;
}

function getDatiPersonaliImpresa($currentuser) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    $sql = "SELECT A.UserID, A.Nome, A.Cognome, I.Nome AS NomeImpresa,
                   IFNULL(B.RisposteFatte,0) AS RisposteFatte , IFNULL(C.RisposteAccettate,0) AS RisposteAccettate, 
                   IFNULL(D.RisposteRifiutate,0) AS RisposteRifiutate, IFNULL(E.RecensioniFatte,0) AS RecensioniFatte,
                   IFNULL(F.RecensioniRicevute,0) AS RecensioniRicevute, IFNULL(G.RatingMedio,0) AS RatingMedio
            FROM Utente A INNER JOIN Impresa I ON A.UserID = I.Responsabile
                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RisposteFatte
                                            FROM Risposta
                                            GROUP BY Utente
                                            ) B ON A.UserID = B.Utente
                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RisposteAccettate
                                            FROM Risposta 
                                            WHERE StatoRisposta = 'accettata'
                                            GROUP BY Utente
                                            ) C ON A.UserID = C.Utente
                            LEFT OUTER JOIN (SELECT Utente, COUNT(*) AS RisposteRifiutate
                                            FROM Risposta A 
                                            WHERE StatoRisposta = 'rifiutata'
                                            GROUP BY Utente
                                            ) D ON A.UserID = D.Utente
                            LEFT OUTER JOIN (SELECT Recensore, COUNT(*) AS RecensioniFatte
                                            FROM Recensione
                                            GROUP BY Recensore
                                            ) E ON A.UserID = E.Recensore
                            LEFT OUTER JOIN (SELECT Recensito, COUNT(*) AS RecensioniRicevute
                                            FROM Recensione
                                            GROUP BY Recensito
                                            ) F ON A.UserID = F.Recensito
                            LEFT OUTER JOIN (SELECT Recensito, AVG(Rating) AS RatingMedio
                                            FROM Recensione
                                            GROUP BY Recensito
                                            ) G ON A.UserID = G.Recensito
            WHERE A.UserID = :user;";

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':user', $currentuser);
    $statement->execute();
    $row = $statement->fetch();

    echo '<p><strong>Nome Impresa :  </strong>'.$row['NomeImpresa'].'</p>';
    echo '<p><strong>Nome responsabile :  </strong>'.$row['Nome'].'</p>';
    echo '<p><strong>Cognome responsabile: </strong>'.$row['Cognome'].'</p>';
    echo '<p><strong>Risposte inviate: </strong>'.$row['RisposteFatte'].'</p>';
    echo '<p><strong>Risposte Accettate: </strong>'.$row['RisposteAccettate'].'</p>';
    echo '<p><strong>Risposte Rifiutate: </strong>'.$row['RisposteRifiutate'].'</p>';
    echo '<p><strong>Recensioni Fatte: </strong>'.$row['RecensioniFatte'].'</p>';
    echo '<p><strong>Recensioni Ricevute: </strong>'.$row['RecensioniRicevute'].'</p>';
    echo '<p><strong>Rating Medio: </strong>'.$row['RatingMedio'].'</p>';

    $connection->close();
    $pdo = null;
}

function getArea($user, $areatype) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    switch($areatype) {
        case 'Regione':
            $sql = "SELECT C.Regione as Area
                    FROM Comune C
                    WHERE C.id = (SELECT I.Comune 
                                  FROM Impresa I
                                  WHERE I.Responsabile = :user)";
        break;
        case 'Provincia':
            $sql = "SELECT C.Provincia as Area
                    FROM Comune C
                    WHERE C.id = (SELECT I.Comune 
                                  FROM Impresa I
                                  WHERE I.Responsabile = :user)";
        break;
        case 'Comune':
            $sql = "SELECT C.Comune as Area
                    FROM Comune C
                    WHERE C.id = (SELECT I.Comune 
                                  FROM Impresa I
                                  WHERE I.Responsabile = :user)";
        break;
    }

    $statement = $pdo->prepare($sql);
    $statement->bindValue( ':user', $user);
    $statement->execute();
    $row = $statement->fetch();
    $connection->close();
    $pdo = null;

    return $row['Area'];
}

function getRichiesteRegione($area, $user) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    $sql = "SELECT IDRichiesta
            FROM Richiesta R                          
            WHERE  R.Regione = :area
                    AND NOT EXISTS (SELECT *
                                    FROM Risposta T
                                    WHERE R.IDRichiesta = T.Richiesta AND T.Utente = :utente) 
                    AND StatoRichiesta = 'inviata' ";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':area', $area);
    $statement->bindValue(':utente', $user);
    $statement->execute();

    $richieste = [];
    while ($row = $statement->fetch()) {
        $richieste[] = new Richiesta($row['IDRichiesta']);
    }

    $connection->close();
    $pdo = null;

    return $richieste;
}

function getRichiesteProvincia($area, $user) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    $sql = "SELECT IDRichiesta
            FROM Richiesta R                          
            WHERE  R.Provincia = :area
                    AND NOT EXISTS (SELECT *
                                    FROM Risposta T
                                    WHERE R.IDRichiesta = T.Richiesta AND T.Utente = :utente) 
                    AND StatoRichiesta = 'inviata' ";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':area', $area);
    $statement->bindValue(':utente', $user);
    $statement->execute();

    $richieste = [];
    while ($row = $statement->fetch()) {
        $richieste[] = new Richiesta($row['IDRichiesta']);
    }

    $connection->close();
    $pdo = null;

    return $richieste;
}

function getRichiesteComune($area, $user) {
    $connection = new connectDB();
    $pdo = $connection->getPDO();

    $sql = "SELECT IDRichiesta
            FROM Richiesta R                          
            WHERE  R.Comune = (SELECT C.id FROM Comune C WHERE C.Comune = :area)
                    AND NOT EXISTS (SELECT *
                                    FROM Risposta T
                                    WHERE R.IDRichiesta = T.Richiesta AND T.Utente = :utente) 
                    AND StatoRichiesta = 'inviata' ";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':area', $area);
    $statement->bindValue(':utente', $user);
    $statement->execute();

    $richieste = [];
    while ($row = $statement->fetch()) {
        $richieste[] = new Richiesta($row['IDRichiesta']);
    }

    $connection->close();
    $pdo = null;

    return $richieste;
}


?>