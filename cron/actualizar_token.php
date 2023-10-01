<?php

    require_once '../clases/token.class.php';

    date_default_timezone_set("America/Lima");
    
    $_token = new token;
    // $fecha = date('Y-m-d H:i');
    $fecha = date('Y-m-d');
    echo $_token->actualizarTokens($fecha);

?>