<?php


    function generateRandomSalt() {
    
        return base64_encode(random_bytes(12));
    }
    
    

?>