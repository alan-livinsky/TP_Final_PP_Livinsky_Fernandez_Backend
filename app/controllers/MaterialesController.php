<?php

class MaterialesController{

  public static function retornarListaMateriales($request,$response,$args){
    $listaMateriales=Materiales::buscar_lista_materiales();
    $response->getBody()->write(json_encode($listaMateriales));
    return $response;
  }

  public static function retornarDatosMaterial($request,$response,$args){
    $listaMateriales=Materiales::buscarDatosMaterial($args['material']);
    $response->getBody()->write(json_encode($listaMateriales));
    return $response;
  }

}
?>