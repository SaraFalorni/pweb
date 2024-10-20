const ProvAbruzzo = ["L'Aquila", "Teramo", "Pescara", "Chieti"];
const ProvBasilicata = ["Potenza", "Matera"];
const ProvCalabria = ["Catanzaro", "Cosenza", "Crotone", "Reggio Calabria", "Vibo Valentia"];
const ProvCampania = ["Avellino", "Benevento", "Caserta", "Napoli", "Salerno"];
const ProvEmiliaRomagna = ["Bologna", "Ferrara", "Forlì-Cesena", "Modena", "Parma", "Piacenza", "Ravenna", "Reggio Emilia", "Rimini"];
const ProvFriuliVeneziaGiulia = ["Gorizia", "Pordenone", "Trieste", "Udine"];
const ProvLazio = ["Frosinone", "Latina", "Rieti", "Roma", "Viterbo"];
const ProvLiguria = ["Genova", "Imperia", "La Spezia", "Savona"];
const ProvLombardia = ["Bergamo", "Brescia", "Como", "Cremona", "Lecco", "Lodi", "Mantova", "Milano", "Monza e Brianza", "Pavia", "Sondrio", "Varese"];
const ProvMarche = ["Ancona", "Ascoli Piceno", "Fermo", "Macerata", "Pesaro e Urbino"];
const ProvMolise = ["Campobasso", "Isernia"];
const ProvPiemonte = ["Alessandria", "Asti", "Biella", "Cuneo", "Novara", "Torino", "Verbano-Cusio-Ossola", "Vercelli"];
const ProvPuglia = ["Bari", "Barletta-Andria-Trani", "Brindisi", "Foggia", "Lecce", "Taranto"];
const ProvSardegna = ["Cagliari", "Nuoro", "Oristano", "Sassari", "Sud Sardegna"];
const ProvSicilia = ["Agrigento", "Caltanissetta", "Catania", "Enna", "Messina", "Palermo", "Ragusa", "Siracusa", "Trapani"];
const ProvToscana = ["Arezzo", "Firenze", "Grosseto", "Livorno", "Lucca", "Massa-Carrara", "Pisa", "Pistoia", "Prato", "Siena"];
const ProvTrentinoAltoAdige = ["Bolzano", "Trento"];
const ProvUmbria = ["Perugia", "Terni"];
const ProvValledAosta = ["Aosta"];
const ProvVeneto = ["Belluno", "Padova", "Rovigo", "Treviso", "Venezia", "Verona", "Vicenza"];


function NewUserIsBuilder()
{
    if(document.getElementById("impresa").checked){
        document.getElementById("Impresa").style.display = "block";
    }
    else
        document.getElementById("Impresa").style.display = "none";
}

function createOptionProvincia(prov) {
    const option = document.createElement("option");
    const name = document.createTextNode(prov);
    option.value = prov;
    option.appendChild(name);
    document.getElementById("Provincia").appendChild(option);
}

function emptyOptionsProvincia() {
    var prov = document.getElementById("Provincia");
    var i;
    var L = prov.options.length - 1;
   for(i = L; i >= 0; i--) {
      prov.remove(i);
   }
    //creo la opzione di default
    const option = document.createElement("option");
    const name = document.createTextNode(" -- Scegli la provincia -- ");
    option.value = "";
    option.appendChild(name);
    document.getElementById("Provincia").appendChild(option);
}

function findProvincia()
{
    //emptyOptionsProvincia();
    var selectedValue = document.getElementById("Regione").value;
    switch(selectedValue) {
        case "Abruzzo": 
            ProvAbruzzo.forEach(createOptionProvincia); 
            break;

        case "Basilicata": 
            ProvBasilicata.forEach(createOptionProvincia); 
            break;

        case "Calabria": 
            ProvCalabria.forEach(createOptionProvincia); 
            break;

        case "Campania": 
            ProvCampania.forEach(createOptionProvincia); 
            break;

        case "Emilia Romagna": 
            ProvEmiliaRomagna.forEach(createOptionProvincia); 
            break;

        case "Friuli Venezia Giulia": 
            ProvFriuliVeneziaGiulia.forEach(createOptionProvincia); 
            break;

        case "Lazio": 
            ProvLazio.forEach(createOptionProvincia); 
            break;

        case "Liguria": 
            ProvLiguria.forEach(createOptionProvincia); 
            break;

        case "Lombardia": 
            ProvLombardia.forEach(createOptionProvincia); 
            break;

        case "Marche": 
            ProvMarche.forEach(createOptionProvincia); 
            break;

        case "Molise": 
            ProvMolise.forEach(createOptionProvincia); 
            break;

        case "Piemonte": 
            ProvPiemonte.forEach(createOptionProvincia); 
            break;

        case "Puglia": 
            ProvPuglia.forEach(createOptionProvincia); 
            break;

        case "Sardegna": 
            ProvSardegna.forEach(createOptionProvincia); 
            break;

        case "Sicilia": 
            ProvSicilia.forEach(createOptionProvincia); 
            break;

        case "Toscana": 
            ProvToscana.forEach(createOptionProvincia); 
            break;

        case "Trentino Alto Adige": 
            ProvTrentinoAltoAdige.forEach(createOptionProvincia); 
            break;

        case "Umbria": 
            ProvUmbria.forEach(createOptionProvincia); 
            break;

        case "Val D’Aosta": 
            ProvValledAosta.forEach(createOptionProvincia); 
            break;

        case "Veneto": 
            ProvVeneto.forEach(createOptionProvincia); 
            break;
       
        default:
            console.log("Regione non trovata");       

    }
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

function LoadComuni() {

   var prov = document.getElementById("Provincia").value;
    var sel = document.getElementById("Comune");
    /*sel.innerHTML = `<a href='./listComuni.php?Provincia=Livorno'> </a>`;*/


    var xhr = new XMLHttpRequest(); 
    xhr.open("GET", "../php/listComuni.php?Provincia=" + prov, true); 
    xhr.onload = function() 
    { if (xhr.status === 200) 
            { var data = xhr.responseText; 
                console.log(data);
                sel.innerHTML = data;
           // }
      } 
    }; 
    xhr.send(); 

}

function checkBirthDate() {
   /*var birthDate = document.getElementById("birthDate").value;
    var today = new Date();
    var tyear = today.getFullYear();
    if( (tyear - birthDate.getFullYear()) < 18) {
        document.getElementById("errorBirthDate").style.display = "block";
    }
    else
        document.getElementById("errorBirthDate").style.display = "none";   */ 

}


