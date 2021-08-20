<?php

  function retornarListaEjerciciosMenu($request,$response,$args){
    $ejerciciosMenuPrincipal=buscar_lista_ejercicios();
    $response->getBody()->write(json_encode($ejerciciosMenuPrincipal));
    return $response;
  }

?>