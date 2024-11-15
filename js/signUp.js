function NewUserIsBuilder()
{
    if(document.getElementById("impresa").checked){
        document.getElementById("Impresa").style.display = "block";
    }
    else
        document.getElementById("Impresa").style.display = "none";
}

function checkPasswordString() {
    var pass = document.getElementById("pwd");
    var cpass = document.getElementById("cpwd");
    if(pass.value != cpass.value) {
        var styles = '#cpwd {border: solid 2px red;}';
        var styleSheet = document.createElement("style");
        styleSheet.textContent = styles;
        cpass.appendChild(styleSheet);
        document.getElementById("errorPwd").style.display = "block";
    }
    else
    {
        var styles = '#cpwd {border: solid 2px rgb(16, 16, 155);}';
        var styleSheet = document.createElement("style");
        styleSheet.textContent = styles;
        cpass.appendChild(styleSheet);
        document.getElementById("errorPwd").style.display = "none";
    }
}


function checkBirthDate() {
    // Ricava la data di nascita dall'input come timestamp e convertila in un oggetto Date
    var birthdayTimestamp = Date.parse(document.getElementById("birthDate").value);
    var birthday = new Date(birthdayTimestamp);

    // Ottieni la data odierna come oggetto Date
    var today = new Date();
    var tyear = today.getFullYear();

    var bdate = document.getElementById("birthDate");
    // Calcola l'età
    if ((tyear - birthday.getFullYear()) < 18) {
        document.getElementById("errorBirthDate").style.display = "block";
        var styles = '#birthDate {border: solid 2px red;}';
        var styleSheet = document.createElement("style");
        styleSheet.textContent = styles;
        bdate.appendChild(styleSheet);
        
    } else {
        document.getElementById("errorBirthDate").style.display = "none"; 
        var styles = '#birthDate {border: solid 2px rgb(16, 16, 155);}';
        var styleSheet = document.createElement("style");
        styleSheet.textContent = styles;
        bdate.appendChild(styleSheet); 
    }
}


