<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      $url=new MenuPrincipal();
      return $response->getBody()->write($url->getURL());
  }







}


?>