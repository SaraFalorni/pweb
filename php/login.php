<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/login.css"> 
        <link rel="stylesheet" type="text/css" href="../css/homepage.css"> 
    </head>
<body>
<header>
    
    <img src="logo.png" alt="Logo"> <span>&nbsp;My website</span>

  </header>
   <nav>
    <ul>
      <li><a href="./Homepage.php">Home</a></li>
      <li><a href="#" id="info" >Informazioni sul sito</a></li>
      <li><a href="../html/signUp.php">Registrati</a></li>
      <li><a href="./login.php">Accedi</a></li>
    </ul>
  </nav>
  <hr>
  
    <div id="credenziali"> <h1>Effettua il Login!</h1>
        <form action="./doLogin.php" method="GET" id="fcredenziali">
        UserID <br/> <input type="text" class="LoginInput" name="user"> <br/> <br/>
        Password <br/> <input type="password" class="LoginInput" name="pwd"> <br/>
        </br> &ensp;
        <input type="submit" value="Login">
        </form>  

    </div>
</body>