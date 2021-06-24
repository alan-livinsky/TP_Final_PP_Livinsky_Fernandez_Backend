<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      $url=new MenuPrincipal();
      $response->getBody()->write($url->getURL());
      return $response; 
    }

}


?>