function GetRisposte(idRic) {
    var risposte = document.getElementById("divRisposte"+ idRic);
    // ***
    var pnlRisposta=document.getElementById("pnlRisposta"+ idRic);
    var xhr = new XMLHttpRequest(); 
    xhr.open("GET", "./getRisposte.php?richiesta=" + idRic, true); 
    xhr.onload = function() 
            { if (xhr.status === 200) 
                { var data = xhr.responseText; 
                //console.log(data);
                //risposte.innerHTML = data;   
                pnlRisposta.innerHTML = data;
                pnlRisposta.style.overflow="visible";
                pnlRisposta.style.height=pnlRisposta.scrollHeight + "px";
                pnlRisposta.children[0].style.overflow="visible";
                pnlRisposta.children[0].style.height=pnlRisposta.scrollHeight + "px";   
                } 
            }; 
    xhr.send(); 
}
function ChangeRispostaStatus(status,idRic) {
    var ris = document.getElementById("divRisposta"+idRic);
    var IDRisp = document.getElementById("IHRisposta"+idRic).value; 
    var params = "stato=" + status + "&idRisp=" + IDRisp + "&idRic=" + idRic;
    var xhr = new XMLHttpRequest(); 
    xhr.open("POST", "../php/changeRispostaStatus.php", true); 
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() 
    { if (xhr.status === 200) 
            { var data = xhr.responseText; 
                console.log(data);
                //ris.innerHTML = data;
                const div1 = document.getElementById('ric'+idRic);
                const cardDiv = document.createElement('div');
                cardDiv.className = 'card';
                cardDiv.style.position = 'relative'; 
                const containerDiv = document.createElement('div');
                containerDiv.className = 'container';
                const par = document.createElement('p');
                par.innerHTML = 'La risposta è stata '+ status + '<br> Clicca <a href="./archivio.php">qui</a> per visualizzare il riepilogo.';
                containerDiv.appendChild(par);
                cardDiv.appendChild(containerDiv);
                div1.appendChild(cardDiv);
           // }
      } 
    }; 
    xhr.send(params);     

}

function HideButtons(id) {
    var $buttonAccetta = document.getElementById('bAccetta'+id);
    var $buttonRifiuta = document.getElementById('bRifiuta'+id);
    $buttonAccetta.style.display = 'none'; 
    $buttonRifiuta.style.display = 'none';
 
 };