<?php

class UsuarioController{

    public function RetornarUsuario($request,$response,$args){

        $usr=new Usuarios();

        $usr->nombre = "Ezequiel";
        $usr->apellido = "Oggioni";
        $usr->pathImagen = "imagen54.jpg";
        
        $response->getBody()->Write(json_encode($usr) );

        return $response;
    }

    public function RetornarTodos($request,$response,$args){
        $array=Usuarios::obtenerUsuarios();
        $response->getBody()->write(json_encode($array));
        return $response;
    }

}


?>