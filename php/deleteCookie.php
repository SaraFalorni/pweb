<?php
if (isset($_COOKIE['user'])) {
    // Elimina il cookie impostandolo con una scadenza nel passato
    setcookie("user", "", time() - 3600, "/");
} else {
    echo "Cookie non trovato.";
}

if (isset($_COOKIE['usertype'])) {
    // Elimina il cookie impostandolo con una scadenza nel passato
    setcookie("user", "", time() - 3600, "/");
} else {
    echo "Cookie non trovato.";
}

echo "sessione conclusa correttamente. Arrivederci";
?>