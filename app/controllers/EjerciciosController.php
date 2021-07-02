<?php

class EjerciciosController{

  public static function retornarListaEjerciciosMenu($request,$response,$args){
    $ejerciciosMenuPrincipal=Ejercicios::buscar_lista_ejercicios();
    $response->getBody()->write(json_encode($ejerciciosMenuPrincipal));
    return $response;
  }

}
?>