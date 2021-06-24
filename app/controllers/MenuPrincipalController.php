<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal(){
      return $response->withAddedHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/')
                        ->withStatus(302);
  }







}


?>