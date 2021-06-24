<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      return $response->withAddedHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/Menu_Principal.php')
                        ->withStatus(302);
  }







}


?>