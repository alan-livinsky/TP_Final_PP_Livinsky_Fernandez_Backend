<?php

    function retornarListaCursos($request,$response,$args){
        $listaCursos=buscarlistaDeCursos();

        $extraerAños=$listaCursos[0]["col1_values"];
        $extraerAños=$arr = explode(",", $extraerAños);

        echo($extraerAños);


        $extraerComisiciones=$listaCursos[0]["col2_values"];
        $ExtraerTurnos=$listaCursos[0]["col3_values"];

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