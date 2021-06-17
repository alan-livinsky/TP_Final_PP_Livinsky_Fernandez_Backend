<?php

class UsuariosController{

    
    public function retornarListaUsuarips($request,$response,$args){
        $array=Usuarios::obtenerUsuarios();
        $response->getBody()->write(json_encode($array));
        return $response;
    }

    public static function retornarUsuario($request,$response,$args){
        $usuario=Usuarios::buscar_usuario($args['usuario'],$args['contrasea']);
        $response->getBody()->write(json_encode($usuario));
        return $response->withHeader('Content-type','application/json');
    }

    public static function retornarEstadoRegistro($request,$response,$args){

        
        /*$datosRegistro=json_decode($request->getBody(),true);*/
           /*
        $array=Usuarios::registrarUsuario();*/
        /*$response->getBody()->write(json_encode($array));*/
        $response=$request->getBody();
        return $response;
    }


}


?>