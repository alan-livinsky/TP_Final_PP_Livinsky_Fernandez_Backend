<?php

use Firebase\JWT\JWT;

//SI PASA LA VALIDACION DEL TOKEN EL ACCESO ES VALIDO
  function retornarAccesoMenuPrincipal($request,$response,$args){
    return $response->withStatus(200); 
  }

  function mantenerAccesoPagina($request,$response,$args){
    //El token llega por el header,autorization bearer.
    $JWT = $request->getBody();
    $datosUsuario=JWT::decode($JWT,$_ENV['JWT_SECRET'],array('HS256'));

    var_dump($datosUsuario);

    $cursosAsociados=buscarCursosAsociados($datosUsuario->sub);
    $datosUsuario->cua=$cursosAsociados;

    $response->getBody()->write(json_encode($datosUsuario));
    return $response;
  }

?>