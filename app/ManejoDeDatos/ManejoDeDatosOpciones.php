<?php

    class OpcionesController{

        public static function retornarOpciones_profesor($request,$response,$args){
            $opcionesProfesor=Opciones::buscar_opciones_profesor();
            $response->getBody()->write(json_encode($opcionesProfesor));
            return $response;
          }

    }

?>