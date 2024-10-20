function RequestsinArea() {
    var area = document.getElementById("area").value;
    var ric = document.getElementById("richieste");
    console.log(area);
    var xhr = new XMLHttpRequest(); 
    xhr.open("GET", "./getRequest.php?area=" + area, true); 
    xhr.onload = function() 
            { if (xhr.status === 200) 
                { var data = xhr.responseText; 
                console.log(data);
                ric.innerHTML = data;            
                } 
            }; 
    xhr.send(); 

}

function openFormRisp(idRichiesta,idUser) {
   document.getElementById("dRisposta").style.display = "block";
   document.getElementById('selRichiesta').value = idRichiesta;
   document.getElementById('selUser').value = idUser;
   
}

function closeFormRisp() {
    document.getElementById("dRisposta").style.display = "none";
}

function sendRisposta() {
    document.getElementById("dRisposta").style.display = "none";
    var newDiv = document.createElement('div');
    newDiv.id = 'divRispInviata';
    newDiv.innerHTML = "<p>Risposta inviata con successo! </p>";
    var div = document.getElementById("richiesta");
    div.appendChild(newDiv);
}
