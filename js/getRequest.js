function addSeparatore(divitem) {
    var sep = document.createElement("div");
    sep.class ="separatore";
    sep.textContent = "    ";
    divitem.appendChild(sep);
}

function RequestsinArea(currentuser) {
    var areatype = document.getElementById("areatype").value;
    console.log(areatype);
    var xhr = new XMLHttpRequest(); 
    xhr.open("GET", "./getRequest.php?areatype=" + areatype, true); 
    xhr.onload = function() 
            { if (xhr.status === 200) 
                { var richiesteArea = JSON.parse(xhr.responseText); 
                var richieste = richiesteArea.richieste;
                outputRichiesteCerca(richieste, currentuser);     
                } 
            }; 
    xhr.send(); 
}

function outputRichiesteCerca(richieste, currentuser) {
    var divric = document.getElementById("divrichieste");
    while(divric.firstChild) {
        divric.removeChild(divric.firstChild);
    }
    if(richieste.length == 0) {
        console.log("non ci sono richieste nell'area selezionata");
        var p1 = document.createElement('p');
        p1.textContent = " Nessuna richiesta nell'area selezionata! ";
        divric.appendChild(p1);

        var p1 = document.createElement('p');
        p1.textContent = " Potresti aver già risposto a tutte le richieste disponibili, ";
        divric.appendChild(p1);

        var p1 = document.createElement('p');
        p1.textContent = "prova ad allargare l'area di ricerca o ricaricare la pagina per nuove richieste!";
        divric.appendChild(p1);
    }
    else {
        console.log("ci sono richieste nell'area selezionata: ");
        richieste.forEach(function (item, index){ outputRichiestainCerca(item,currentuser); });
    }
}

function outputRichiestainCerca(item, currentuser) {

    var divr = document.createElement('div');
    divr.className = "richiestaincerca";
    divr.id = "richiesta_"+ item.id;
    
    console.log("currentuser: "+currentuser)
    console.log("Richiesta #"+item.id);
    console.log("Richiesta di "+item.utente);

    var p = document.createElement('p');
    p.textContent = "Richiesta #" ;
    var b = document.createElement('b');
    b.textContent = item.id + " di " + item.utente;
    p.appendChild(b);    
    divr.appendChild(p);

    var p0 = document.createElement('p');
    p0.textContent = "per il montaggio di: " ;
    var b0 = document.createElement('b');
    b0.textContent = item.tipoMobile;
    p0.appendChild(b0);    
    divr.appendChild(p0);

    //Ulteriori informazioni
    var p1 = document.createElement('p');
    p1.id = "moreinfocerca_"+ item.id + "_1" ;
    p1.style.display = "none";
    p1.textContent = "nel comune di: " ;
    var b1 = document.createElement('b');
    b1.textContent = item.comune;
    p1.appendChild(b1);    
    divr.appendChild(p1);

    var p2 = document.createElement('p');
    p2.id = "moreinfocerca_"+ item.id + "_2" ;
    p2.style.display = "none";
    p2.textContent = "nella data: " ;
    var b2 = document.createElement('b');
    b2.textContent = item.data;
    p2.appendChild(b2);    
    divr.appendChild(p2);

    var p3 = document.createElement('p');
    p3.id = "moreinfocerca_"+ item.id + "_3";
    p3.style.display = "none";
    p3.textContent = "nella fascia oraria: " ;
    var b3 = document.createElement('b');
    b3.textContent = item.fasciaOraria;
    p3.appendChild(b3);    
    divr.appendChild(p3);

    if(item.note != ' ') {
        var p4 = document.createElement('p');
        p4.id = "moreinfocerca_"+ item.id + "_4";
        p4.style.display = "none";
        p4.textContent = item.utente + " scrive: " ;
        var b4 = document.createElement('b');
        b4.textContent = item.note;
        p4.appendChild(b4);    
        divr.appendChild(p4);
    }

    if(item.link) {
        var p5 = document.createElement('p');
        p5.id = "moreinfocerca_"+ item.id + "_5";
        p5.style.display = "none";
        var a5 = document.createElement('a');
        a5.href = item.link;
        a5.textContent = "link al mobile ";
        p5.appendChild(a5);    
        divr.appendChild(p5);
    }

    var p6 = document.createElement('p');
    p6.id = "moreinfocerca_"+ item.id + "_6";
    p6.style.display = "none"; 
    p6.textContent = " per leggere le recensioni ricevute da " + item.utente; 
    var a6 = document.createElement('a');
    a6.href = "checkRecensioni.php?utente=" + currentuser; 
    a6.textContent = " clicca qui";
    p6.appendChild(a6);  
    divr.appendChild(p6);

    var p3 = document.createElement('p');
    p3.textContent = "Rispondi alla richiesta!" ;
    var i1 = document.createElement("input");
    i1.type = "button";
    i1.id = "btnrispondi_"+ item.id;
    i1.class = "btn btn-smaller";
    i1.value = "Rispondi";
    i1.style.marginLeft = "10px";
    i1.addEventListener("click", openFormRisp) ;
    p3.appendChild(i1);
    divr.appendChild(p3);

    var i4 = document.createElement("input");
    i4.type = "button";
    i4.id = "btnmoreinfo_"+ item.id;
    i4.class = "btn btn-smaller";
    i4.value = "Maggiori informazioni";
    i4.style.marginLeft = "10px";
    i4.addEventListener("click", showMoreRicInfo);
    p3.appendChild(i4);
    divr.appendChild(p3);
   
    var i2 = document.createElement("input");
    i2.type = "hidden";
    i2.id = "iIDRichiesta"+ item.id;
    i2.name = "iIDRichiesta"+ item.id;
    i2.value = item.id;
    divr.appendChild(i2);

    var i3 = document.createElement("input");
    i3.type = "hidden";
    i3.id = "iIDUser"+ item.id;
    i3.name = "iIDUser"+ currentuser;
    i3.value = currentuser;
    divr.appendChild(i3);

    var divric = document.getElementById("divrichieste");
    divric.appendChild(divr);

    addSeparatore(divric);

}

function showMoreRicInfo() {
    var tar = event.currentTarget;
    var tarid = tar.id;
    var t = tarid.split("_");
    
    for(var i = 1 ; i <= 6 ; i++) {
        var x = document.getElementById("moreinfocerca_"+t[1]+"_"+i);
        console.log("moreinfocerca_"+t[1]+"_"+i);
        if(x)
            {x.style.display = "block";}
    }
    event.currentTarget.removeEventListener("click", showMoreRicInfo);
    event.currentTarget.addEventListener("click", showLessRicInfo );
    document.getElementById("btnmoreinfo_"+t[1]).value = "Meno informazioni";
}

function showLessRicInfo() {
    var tar = event.currentTarget;
    var tarid = tar.id;
    var t = tarid.split("_");
    
    for(var i = 1 ; i <= 6 ; i++) {
        var x = document.getElementById("moreinfocerca_"+t[1]+"_"+i);
        if(x)
            {x.style.display = "none";} 
    }
    event.currentTarget.removeEventListener("click", showLessRicInfo);
    event.currentTarget.addEventListener("click", showMoreRicInfo );
    document.getElementById("btnmoreinfo_"+t[1]).value = "Maggiori informazioni";
}



function openFormRisp() {
    var targetid = event.target.id;
    var t = targetid.split("_"); //t[1] = idrichiesta

    var divrisp = document.createElement("div");
    divrisp.id = "dRisposta_" + t[1];
    var main = document.getElementById("richiesta_"+t[1]);
    main.appendChild(divrisp);

    var formrisp = document.createElement("form");
    formrisp.action = "../php/sendRisposta.php";
    formrisp.method = "POST";
    divrisp.appendChild(formrisp);

    var p1 = document.createElement("p");
    p1.textContent = "Scrivi qualcosa che vuoi far sapere al cliente! (richieste particolari, messaggi etc.) ";
    formrisp.appendChild(p1);

    var t1 = document.createElement("textarea");
    t1.type = "textarea";
    t1.id = "inRisposta";
    t1.name = "inRisposta";
    formrisp.appendChild(t1);

    var i2 = document.createElement("input");
    i2.type = "hidden";
    i2.id = "selRichiesta";
    i2.name = "selRichiesta";
    i2.value = t[1];
    formrisp.appendChild(i2);

    var i3 = document.createElement("input");
    i3.type = "hidden";
    i3.id = "selUser";
    i3.name = "selUser";
    i3.value = document.getElementById("iIDUser"+ t[1]).value;
    formrisp.appendChild(i3);

    var bsubmit = document.createElement("button");
    bsubmit.type = "submit";
    bsubmit.id = t[1];
    bsubmit.className = "btn btn-smaller";
    bsubmit.addEventListener("click", sendRisposta);
    bsubmit.textContent = "Invia";
    formrisp.appendChild(bsubmit);

    var bchiudi = document.createElement("button");
    bchiudi.type = "button";
    bchiudi.id = t[1];
    bchiudi.className = "btn btn-smaller";
    bchiudi.addEventListener("click", closeFormRisp);
    bchiudi.textContent = "Chiudi";
    formrisp.appendChild(bchiudi);

}

function closeFormRisp() {
    var targetid = event.target.id;
    var main = document.getElementById("richiesta_"+targetid);
    var child = document.getElementById("dRisposta_"+targetid);
    console.log(main);
    main.removeChild(child);
}

function sendRisposta() {
    var targetid = event.target.id;
    document.getElementById("dRisposta_"+targetid).style.display = "none";    
}


