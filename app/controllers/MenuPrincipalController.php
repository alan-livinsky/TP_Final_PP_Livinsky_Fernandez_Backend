<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      $url=MenuPrincipal::getURL();
      return $response->getBody()->write($url);
  }







}


?>