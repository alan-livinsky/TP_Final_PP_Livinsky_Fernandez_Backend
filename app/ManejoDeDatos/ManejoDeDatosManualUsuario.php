<?php

    function retornarResultadoBuscarListaManualUsuario($request,$response,$args){
        $listaVisualizacionManual=buscarManuales($args['opcion']);
        $response->getBody()->write(json_encode($listaVisualizacionManual));
        return $response;
    }

?>