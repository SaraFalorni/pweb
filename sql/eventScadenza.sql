DROP EVENT IF EXISTS ControllaScadenzaRichiesta;
CREATE EVENT ControllaScadenzaRichiesta
ON SCHEDULE EVERY 2 HOUR
DO
	UPDATE Richiesta
    SET StatoRichiesta = 'scaduta'
    WHERE DataRichiesta = CURRENT_DATE() AND RispostaAccettata IS NULL 
		AND (
				(FasciaOraria = '8:00 - 10:00' AND HOUR(NOW()) >= 8) 
			 OR (FasciaOraria = '10:00 - 12:00' AND HOUR(NOW()) >= 10) 
			 OR (FasciaOraria = '12:00 - 14:00' AND HOUR(NOW()) >= 12) 
			 OR (FasciaOraria = '14:00 - 16:00' AND HOUR(NOW()) >= 14)
			 OR (FasciaOraria = '16:00 - 18:00' AND HOUR(NOW()) >= 16) 
			 OR (FasciaOraria = '18:00 - 20:00' AND HOUR(NOW()) >= 18)
			);
        
DROP EVENT IF EXISTS ControllaConclusioneRichiesta;
CREATE EVENT ControllaConclusioneRichiesta
ON SCHEDULE EVERY 2 HOUR
DO
	UPDATE Richiesta
    SET StatoRichiesta = 'conclusa'
    WHERE DataRichiesta = CURRENT_DATE() AND RispostaAccettata IS NOT NULL 
		AND (
				(FasciaOraria = '8:00 - 10:00' AND HOUR(NOW()) >= 10) 
			 OR (FasciaOraria = '10:00 - 12:00' AND HOUR(NOW()) >= 12) 
			 OR (FasciaOraria = '12:00 - 14:00' AND HOUR(NOW()) >= 14) 
			 OR (FasciaOraria = '14:00 - 16:00' AND HOUR(NOW()) >= 16)
			 OR (FasciaOraria = '16:00 - 18:00' AND HOUR(NOW()) >= 18) 
			 OR (FasciaOraria = '18:00 - 20:00' AND HOUR(NOW()) >= 20)
			);
        
DROP EVENT IF EXISTS ControllaScadenzaRisposta;
CREATE EVENT ControllaScadenzaRisposta
ON SCHEDULE EVERY 2 HOUR
DO
	UPDATE Risposta
    SET StatoRisposta = 'scaduta'
    WHERE CURRENT_DATE() = (SELECT DataRichiesta
							FROM Richiesta
                            WHERE IDRichiesta = Risposta.Richiesta)
		AND  StatoRisposta = 'inviata' AND
		(
				(FasciaOraria = '8:00 - 10:00' AND HOUR(NOW()) >= 8) 
			 OR (FasciaOraria = '10:00 - 12:00' AND HOUR(NOW()) >= 10) 
			 OR (FasciaOraria = '12:00 - 14:00' AND HOUR(NOW()) >= 12) 
			 OR (FasciaOraria = '14:00 - 16:00' AND HOUR(NOW()) >= 14)
			 OR (FasciaOraria = '16:00 - 18:00' AND HOUR(NOW()) >= 16) 
			 OR (FasciaOraria = '18:00 - 20:00' AND HOUR(NOW()) >= 18)
			);
        
DROP EVENT IF EXISTS ControllaConclusioneRisposta;
CREATE EVENT ControllaConclusioneRisposta
ON SCHEDULE EVERY 2 HOUR
DO
	UPDATE Risposta
    SET StatoRisposta = 'conclusa'
    WHERE CURRENT_DATE() = (SELECT DataRichiesta
							FROM Richiesta
                            WHERE IDRichiesta = Risposta.Richiesta)
		AND StatoRisposta = 'accettata' AND
		(
			(FasciaOraria = '8:00 - 10:00' AND HOUR(NOW()) >= 10) 
			 OR (FasciaOraria = '10:00 - 12:00' AND HOUR(NOW()) >= 12) 
			 OR (FasciaOraria = '12:00 - 14:00' AND HOUR(NOW()) >= 14) 
			 OR (FasciaOraria = '14:00 - 16:00' AND HOUR(NOW()) >= 16)
			 OR (FasciaOraria = '16:00 - 18:00' AND HOUR(NOW()) >= 18) 
			 OR (FasciaOraria = '18:00 - 20:00' AND HOUR(NOW()) >= 20)
			);