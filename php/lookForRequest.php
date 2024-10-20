<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
        <script type="text/javascript" src="../js/getRequest.js"></script> 
    </head>
<body>
    <header>
    
    <img src="logo.png" alt="Logo"> <span>&nbsp;My website</span>

   </header>
   <nav>
    <ul>
      <li><a href="./indexImpresa.php">Home</a></li>
      <li><a href="./lookForRequest.php" id="lookForReq" >Cerca nuove richieste</a></li>
      <li><a href="./archivio.php">Archivio Risposte</a></li>
      <li><a href="./areaPersonale.php">Area Personale</a></li>
      <li><a href="./homepage.php" id="logoutButton"> Logout </a></li>
    </ul>
  </nav>
  <hr>
    <h1>Richieste</h1> <br/>
    <label for="area" >Mostra richieste disponibile nel</label>
    <select name="area" id="area" >
        <option disabled selected value> -- Scegli l'area -- </option>
        <option value="Regione"> Regione </option>
        <option value="Provincia"> Provincia </option>
        <option value="Comune"> Comune </option>
    </select>
    &nbsp;
    <input type="button" id="btnaggiorna" onclick="RequestsinArea()"> Aggiorna </input>
    <div id="richieste">
    </div>
    <div id="dRisposta" style="display:none">
    <form id="formRisposta" action="./sendRisposta.php" method="POST">
        Scrivi qualcosa che vuoi far sapere al cliente! (richieste particolari, messaggi etc.) <br/>
        <textarea type="textarea" id="inRisposta" name="inRisposta"> </textarea> <br/>
        <input type="hidden" id="selRichiesta" name="selRichiesta"  > </input>
        <input type="hidden" id="selUser" name="selUser" > </input>
        <button type="submit" class="btnChiudiRisposta" onclick="sendRisposta()" >Invia</button> &nbsp; 
        <button type="button" class="btnChiudiRisposta" onclick="closeFormRisp()">Chiudi</button>
    </form>
    </div>
    
    

</body>
</html>