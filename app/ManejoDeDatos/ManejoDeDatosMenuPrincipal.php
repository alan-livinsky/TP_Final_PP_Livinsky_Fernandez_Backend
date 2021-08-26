<?php

use Firebase\JWT\JWT;

  function retornarAccesoMenuPrincipal($request,$response,$args){
   // return $response->withStatus(200); 
  return $response->withHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/Menu_Principal.html')->withStatus(302);
  }

  function mantenerAccesoMenuPrincipal($request,$response,$args){
    $JWT = $request->getBody();
    $datosUsuario= JWT::decode($JWT,$_ENV['JWT_SECRET'],array('HS256'));
    $response->getBody()->write(json_encode($datosUsuario));
    return $response;
  }


  
?>