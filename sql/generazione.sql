DROP DATABASE IF EXISTS Falorni_596588;
CREATE DATABASE Falorni_596588;
USE Falorni_596588;

CREATE TABLE Comune (
	id VARCHAR(4) NOT NULL,
    Comune VARCHAR(255),
    Provincia VARCHAR(255),
    PRIMARY KEY (id) 
);

-- Tabella: Impresa
CREATE TABLE Impresa (
	IDImpresa INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(255),
    Descrizione VARCHAR(255),
    Comune VARCHAR(4),
    Responsabile VARCHAR(30) NOT NULL,
    PRIMARY KEY (IDImpresa)
);

-- Tabella: Utente
CREATE TABLE Utente (
    UserID VARCHAR(30) NOT NULL,
    Nome VARCHAR(30),
    Cognome VARCHAR(30),
    Email VARCHAR(255),
    Pwd VARCHAR(128),
    DataNascita DATE,
    Indirizzo VARCHAR(255),
    FotoUser VARCHAR(255),
    PRIMARY KEY (UserID)
);


-- Tabella: Recensione
CREATE TABLE Recensione (
    IDRecensione INT NOT NULL,
    Recensore VARCHAR(30) NOT NULL,
    Recensito VARCHAR(30) NOT NULL,
    TestoRecensione VARCHAR(255),
    Rating INT NOT NULL,
    PRIMARY KEY (IDRecensione)
);


-- Tabella: Richiesta
CREATE TABLE Richiesta (
    IDRichiesta INT NOT NULL,
    TipoMobile VARCHAR(100),
    DataRichiesta DATE,
    FasciaOraria VARCHAR(15) NOT NULL,
    Utente VARCHAR(30) NOT NULL,
    Comune VARCHAR(255),
    Provincia VARCHAR(255),
    Regione VARCHAR(255),
    MessaggioNote VARCHAR(255),
    FotoRichiesta VARCHAR(255),
    LinkRichiesta VARCHAR(255),
    StatoRichiesta VARCHAR(30),
    RispostaAccettata INT,
    TimeStampRichiesta DATETIME,
    PRIMARY KEY (IDRichiesta)
);

-- Tabella: Risposta
CREATE TABLE Risposta (
    IDRisposta INT NOT NULL,
	MessaggioRisposta VARCHAR(255),
    StatoRichiesta VARCHAR(30),
    Richiesta INT,
    Utente VARCHAR(30) NOT NULL,
    TimeStampRisposta DATETIME,
    PRIMARY KEY (IDRisposta)
);



-- Dichiarazione delle chiavi esterne



ALTER TABLE Recensione
    ADD CONSTRAINT FK_Recensione_Utente1
    FOREIGN KEY (Recensore) REFERENCES Utente(UserID),
    ADD CONSTRAINT FK_Recensione_Utente2
    FOREIGN KEY (Recensito) REFERENCES Utente(UserID);
    
ALTER TABLE Richiesta
    ADD CONSTRAINT FK_Richiesta_Utente
    FOREIGN KEY (Utente) REFERENCES Utente(UserID),
    ADD CONSTRAINT FK_Richiesta_Risposta
    FOREIGN KEY (RispostaAccettata) REFERENCES Risposta(IDRisposta);
    
ALTER TABLE Risposta
    ADD CONSTRAINT FK_Risposta_Utente
    FOREIGN KEY (Utente) REFERENCES Utente(UserID),
    ADD CONSTRAINT FK_Risposta_Richiesta
    FOREIGN KEY (Richiesta) REFERENCES Richiesta(IDRichiesta);

ALTER TABLE Impresa
     ADD CONSTRAINT FK_Impresa_Comune
    FOREIGN KEY (Comune) REFERENCES Comune(id),
	ADD CONSTRAINT FK_Impresa_Utente
    FOREIGN KEY (Responsabile) REFERENCES Utente(UserID);
    
ALTER TABLE Comune ADD COLUMN Regione VARCHAR(50);