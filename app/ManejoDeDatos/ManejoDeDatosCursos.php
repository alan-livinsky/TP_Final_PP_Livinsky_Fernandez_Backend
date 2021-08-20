<?php

    function retornarListaCursos($request,$response,$args){
        $listaCursos=buscarlistaDeCursos();

        $extraerAños=$listaCursos[0]["col1_values"];
            str_replace("{","",$extraerAños);
            str_replace("}","",$extraerAños);
        $extraerAños=$arr = explode(",",$extraerAños);

        $extraerComisiones=$listaCursos[0]["col2_values"];
            str_replace("{","",$extraerComisiones);
            str_replace("}","",$extraerComisiones);
        $extraerComisiones=$arr = explode(",",$extraerComisiones);
       
        $ExtraerTurnos=$listaCursos[0]["col3_values"];
            str_replace("{","",$ExtraerTurnos);
            str_replace("}","",$ExtraerTurnos);
        $ExtraerTurnos=$arr = explode(",",$ExtraerTurnos);

        $listaOpcionesDeCurso=[$extraerAños,$extraerComisiones,$ExtraerTurnos];

        $response->getBody()->write(json_encode($listaOpcionesDeCurso));
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