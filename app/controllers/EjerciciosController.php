<?php

class EjerciciosController{

  public static function retornarOpcionesMenuPrincipal($request,$response,$args){
    $opcionesMenuPrincipal=Ejercicios::buscar_opciones_menuPrincipal();
    $response->getBody()->write(json_encode($opcionesMenuPrincipal));
    return $response;
  }

}


?>