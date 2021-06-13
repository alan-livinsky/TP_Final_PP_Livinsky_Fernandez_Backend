<?php

class UsuariosController{

    /*
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
    }*/

    public static function retornarUsuario($request,$response,$args){
        $usuario=Usuarios::buscar_usuario($args['usuario'],$args['contrasea']);
        $response->write(json_encode($usuario));
        return $response->withHeader('Content-type','application/json');;
    }

}


?>