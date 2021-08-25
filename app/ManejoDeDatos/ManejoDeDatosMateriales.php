<?php

  function retornarListaMateriales($request,$response,$args){
    $listaMateriales=buscar_lista_materiales();
    $response->getBody()->write(json_encode($listaMateriales));
    return $response;
  }

  function retornarDatosMaterial($request,$response,$args){
    $listaMateriales=buscarDatosMaterial($args['material']);
    $response->getBody()->write(json_encode($listaMateriales));
    return $response;
  }

?>