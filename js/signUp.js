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
   /*var birthDate = document.getElementById("birthDate").value;
    var today = new Date();
    var tyear = today.getFullYear();
    if( (tyear - birthDate.getFullYear()) < 18) {
        document.getElementById("errorBirthDate").style.display = "block";
    }
    else
        document.getElementById("errorBirthDate").style.display = "none";  
    ***
        */ 

}


