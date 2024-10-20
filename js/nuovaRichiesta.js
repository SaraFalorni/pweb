function LoadComuni() {
    prov = document.getElementById('selProvincia').value;
    sel = document.getElementById('selComune');
    var xhr = new XMLHttpRequest(); 
    xhr.open("GET", "./listComuni.php?Provincia=" + prov, true); 
    xhr.onload = function() 
            { if (xhr.status === 200) 
                { var data = xhr.responseText; 
                console.log(data);
                sel.innerHTML = data;            
                } 
            }; 
    xhr.send(); 
}