<?php

class EjerciciosController{

  public static function retornarEjerciciosMenuPrincipal($request,$response,$args){
    $ejerciciosMenuPrincipal=Ejercicios::buscar_ejercicios_menuPrincipal();
    $response->getBody()->write(json_encode($ejerciciosMenuPrincipal));
    return $response;
  }

}
?>