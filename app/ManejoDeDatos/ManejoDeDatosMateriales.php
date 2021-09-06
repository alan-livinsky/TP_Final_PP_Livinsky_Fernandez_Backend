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

  function retornarEstadoCargaMaterial($request,$response,$args){
    $material=$request->getBody();
    $material=json_decode($material);
    $estadoCargaMaterial=cargarNuevoMaterial($material);
    $response->getBody()->write(json_encode($estadoCargaMaterial));
    return $response->withStatus(500);
    //return $response;
  }

?>