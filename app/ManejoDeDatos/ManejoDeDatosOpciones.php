<?php

    function retornarOpciones_profesor($request,$response,$args){
        $opcionesProfesor=buscar_opciones_profesor();
        $response->getBody()->write(json_encode($opcionesProfesor));
        return $response;
    }

?>