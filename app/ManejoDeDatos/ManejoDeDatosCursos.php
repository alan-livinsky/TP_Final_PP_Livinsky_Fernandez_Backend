<?php

    function retornarListaCursos($request,$response,$args){
        $listaCursos=buscarlistaDeCursos();
        $response->getBody()->write(json_encode($listaOpcionesDeCurso));
        return $response;
    }

    function retornarListaCursosFiltrada($request,$response,$args){
        $listaCursos=buscarlistaDeCursosFiltrada();

        $extraerAños=$listaCursos[0]["col1_values"];
        $extraerAños=str_replace("{","",$extraerAños);
        $extraerAños=str_replace("}","",$extraerAños);
        $extraerAños=$arr = explode(",",$extraerAños);

        $extraerComisiones=$listaCursos[0]["col2_values"];
        $extraerComisiones=str_replace("{","",$extraerComisiones);
        $extraerComisiones=str_replace("}","",$extraerComisiones);
        $extraerComisiones=$arr = explode(",",$extraerComisiones);
    
        $ExtraerTurnos=$listaCursos[0]["col3_values"];
        $ExtraerTurnos=str_replace("{","",$ExtraerTurnos);
        $ExtraerTurnos=str_replace("}","",$ExtraerTurnos);
        $ExtraerTurnos=$arr = explode(",",$ExtraerTurnos);

        $listaOpcionesDeCurso=[$extraerAños,$extraerComisiones,$ExtraerTurnos];

        $response->getBody()->write(json_encode($listaOpcionesDeCurso));
        return $response;
    }

?>