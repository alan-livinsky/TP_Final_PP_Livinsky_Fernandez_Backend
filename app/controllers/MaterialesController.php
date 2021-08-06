<?php

class MaterialesController{

  public static function retornarListaMateriales($request,$response,$args){
    $materiales=new Materiales();
    $listaMateriales=$materiales->buscar_lista_materiales();
    $response->getBody()->write(json_encode($listaMateriales));
    return $response;
  }

  public static function retornarDatosMaterial($request,$response,$args){
    $materiales=new Materiales();
    $listaMateriales=$materiales->buscarDatosMaterial($args['material']);
    $response->getBody()->write(json_encode($listaMateriales));
    return $response;
  }

}
?>