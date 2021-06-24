<?php

class MenuPrincipalController{

  public static function retornarAccesoMenuPrincipal($request,$response,$args){
      $response=MenuPrincipal::getURL();
      return $response;
  }







}


?>