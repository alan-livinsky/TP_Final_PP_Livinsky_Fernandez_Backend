<?php

    function retornarListaNormativas($request,$response,$args){
        $listaNormativa=buscarListaNormativa();
        $response->getBody()->write(json_encode($listaNormativa));
        return $response->withHeader('Content-type','application/json');
    }

?>