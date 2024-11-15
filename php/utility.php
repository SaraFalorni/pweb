<?php
//per il salvataggio sicuro della password

    function generateRandomSalt() {
    
        return base64_encode(random_bytes(12));
    }
    
    

?>