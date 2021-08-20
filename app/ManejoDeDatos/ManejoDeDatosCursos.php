<?php

    function retornarListaCursos($request,$response,$args){
        $listaCursos=buscarlistaDeCursos();
        $response->getBody()->write(json_encode($listaCursos));
        return $response;
    }

    /*
    function retornarCurso($request,$response,$args){
        $listaCursos=buscarlistaDeCursos();
        $response->getBody()->write(json_encode($listaCursos));
        return $response;
    }
    */

?>