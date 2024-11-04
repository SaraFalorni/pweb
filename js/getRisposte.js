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
                ris.innerHTML = data;
           // }
      } 
    }; 
    xhr.send(params); 
}