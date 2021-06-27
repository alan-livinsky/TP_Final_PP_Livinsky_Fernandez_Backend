<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      return $response->withStatus(200); 
  }

  public static function mantenerAccesoMenuPrincipal($request,$response,$args){
    return $response;
  }
}


?>