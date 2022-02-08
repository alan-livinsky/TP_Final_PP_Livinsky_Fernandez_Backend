<?php

function obtenerFechaActual(){
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fechaActual=date('Y-m-d H:i:s');   
    return $fechaActual;
}

?>