<?php

use Firebase\JWT\JWT;

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      return $response->withStatus(200); 
  }

  public static function mantenerAccesoMenuPrincipal($request,$response,$args){
    $JWT = $request->getBody();
    $datosUsuario= JWT::decode($JWT,$_ENV['JWT_SECRET'],array('HS256'));
    $response=json_encode($datosUsuario);
    return $response;
  }
}


?>