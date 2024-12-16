function CloseModificaProfilo() {
    var div = document.getElementById("divModificaProfilo");
    div.style.display = "none";
    //div.removeChild(div.firstElementChild);
}

function ModificaProfilo() {

    var div = document.getElementById("divModificaProfilo");
    div.style.display = "block";

    /*var divModificaProfilo = document.getElementById('divModificaProfilo');
    var divForm = document.createElement("div");
    
   //Titolo
   var titolo = document.createElement("h3");
   titolo.textContent = 'Modifica le tue informazioni personali ';
   divForm.appendChild(titolo);

   //sottotitoloitolo
   var sottotitolo = document.createElement("p");
   sottotitolo.textContent = '* UserId non è modificabile </br> Inserisci la password nel campo \'Conferma password\' per poter effettuare le modifiche!';
   divForm.appendChild(sottotitolo);

    
    var info = document.createElement("p");
    info.textContent = 'Le tue informazioni personali: ';
    divForm.appendChild(info);

    //Creazione form che contiene le informazioni correnti
    var formInfo = document.createElement("form");
    formInfo.action = "../php/modificaProfilo.php";
    formInfo.method = "POST";

    //Input generali utente

    //userID
    var labelUserid = document.createElement("label");
    labelUserid.textContent = 'UserID <br/> ';
    formInfo.appendChild(labelUserid);

    var userid = document.createElement("input");
    userid.type = "text";
    userid.className = "UserInput";
    userid.name = "UserID";
    userid.setAttribute("value", "<?php echo $utente->userID; ?>");
    //value = "<?php echo $utente->userID; ?>";
    userid.readOnly = true;
    labelUserid.for = "UserID";
    formInfo.appendChild(userid);

    //nome
    var labelnome = document.createElement("label");
    labelnome.textContent = '</br> Nome <br/> ';
    formInfo.appendChild(labelnome);

    var nome = document.createElement("input");
    nome.type = "text";
    nome.className = "UserInput";
    nome.name = "nome";
    nome.id = "nome";
    nome.value = "<?php echo $utente->nome; ?>";
    labelnome.for = "nome";
    formInfo.appendChild(nome);

    //cognome
    var labelcognome = document.createElement("label");
    labelcognome.textContent = '</br> Cognome <br/> ';
    formInfo.appendChild(labelcognome);

    var cognome = document.createElement("input");
    cognome.type = "text";
    cognome.className = "UserInput";
    cognome.name = "cognome";
    cognome.id = "cognome";
    cognome.value = "<?php echo $utente->cognome; ?>";
    labelcognome.for = "cognome";
    formInfo.appendChild(cognome);

    //email
    var labelemail = document.createElement("label");
    labelemail.textContent = '</br> Email <br/> ';
    formInfo.appendChild(labelemail);

    var email = document.createElement("input");
    email.type = "text";
    email.className = "UserInput";
    email.name = "email";
    email.id = "email";
    email.value = "<?php echo $utente->email; ?>";
    labelemail.for = "email";
    formInfo.appendChild(email);

    //password
    var labelpwd = document.createElement("label");
    labelpwd.textContent = '</br> Password <br/> ';
    formInfo.appendChild(labelpwd);

    var pwd = document.createElement("input");
    pwd.type = "password";
    pwd.className = "UserInput";
    pwd.name = "pwd";
    pwd.id = "pwd";
    pwd.value = "<?php echo $utente->pwd; ?>";
    labelpwd.for = "pwd";
    formInfo.appendChild(pwd);

    //conferma password
    var labelCpwd = document.createElement("label");
    labelCpwd.textContent = '</br> Conferma password <br/> ';
    formInfo.appendChild(labelCpwd);

    var cpwd = document.createElement("input");
    cpwd.type = "password";
    cpwd.className = "UserInput";
    cpwd.name = "cpwd";
    cpwd.id = "cpwd";
    //cpwd.addEventListener("onblur",checkPasswordString())
    cpwd.placeholder = "inserisci la password per poter inoltrare le modifiche";
    labelCpwd.for = "cpwd";
    formInfo.appendChild(cpwd);

    //data di nascita
    var labeldataNascita = document.createElement("label");
    labeldataNascita.textContent = '</br> Data di nascita <br/> ';
    formInfo.appendChild(labeldataNascita);

    var dataNascita = document.createElement("input");
    dataNascita.type = "date";
    dataNascita.className = "UserInput";
    dataNascita.name = "birthDate";
    dataNascita.id = "birthDate";
    //dataNascita.addEventListener("onblur",checkBirthDate());
    dataNascita.value = "<?php echo $utente->dataNascita; ?>";
    labeldataNascita.for = "birthDate";
    formInfo.appendChild(dataNascita);

    //se è un'impresa
    if( document.getElementById("usertype").value == 'impresa') {
        var divImpresa = document.createElement("div");
        divImpresa.name = "Impresa";
        divImpresa.id = "Impresa";

        //nome impresa
        var labelnomeImpr = document.createElement("label");
        labelnomeImpr.textContent = 'Nome dell\'impresa <br/> ';
        divImpresa.appendChild(labelnomeImpr);

        var nomeImpr = document.createElement("input");
        nomeImpr.type = "text";
        nomeImpr.className = "UserInput";
        nomeImpr.name = "nomeImpresa";
        nomeImpr.id = "nomeImpresa";
        nomeImpr.value = "<?php echo $utente->impresa->nomeImpresa; ?>";
        labelnomeImpr.for = "nomeImpresa";
        divImpresa.appendChild(nomeImpr);

        //Regione
        var labelregione = document.createElement("label");
        labelregione.textContent = '</br> Regione ';
        labelregione.for = "Regione";
        divImpresa.appendChild(labelregione);
        //ARRIVATA QUI C'è DA CREARE SELECT CON LE OPTIONS O USARE FUNZIONI GIA ESISTENTI???

        formInfo.appendChild(divImpresa);
    }    
    //submit button
    var submitbtn = document.createElement("input");
    submitbtn.type ="submit";
    submitbtn.className = "btn btn-smaller";
    submitbtn.value = "Conferma modifiche";
    formInfo.appendChild(submitbtn);

    divForm.appendChild(formInfo);

    divModificaProfilo.appendChild(divForm);    
    //close button
    var closebtn = document.createElement("input");
    closebtn.type ="button";
    closebtn.className = "btn btn-smaller";
    closebtn.addEventListener("onclick", CloseModificaProfilo());
    closebtn.value = "Chiudi";
    divForm.appendChild(closebtn);*/
}