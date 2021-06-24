<?php

use Firebase\JWT\JWT;

class UsuariosController{

    public static function retornarUsuario($request,$response,$args){
        $usuario=Usuarios::buscar_usuario($args['usuario'],$args['contrasea']);
        $response->getBody()->write(json_encode($usuario));
        return $response->withHeader('Content-type','application/json');
    }

    public static function retornarTokenAcceso($request,$response,$args){
        $usuario=Usuarios::buscar_usuario($args['usuario'],$args['contrasea']);
        
        if($usuario==false){
            $response->getBody()->write(json_encode($usuario));
            return $response->withHeader('Content-type','application/json'); 
        }
        else{
            
            $privateKey = $_ENV['JWT_SECRET'];
/*
            $nombre='"'.$usuario->{$nombre}.'"';
            $apellido='"'.$usuario->{$apellido}.'"';
            $tipo='"'.$usuario->{$tipo_usuario}.'"';
            */
            $payload = array(
                "nom" => "dfdffd",
                "ape" => "ssd",
                "tu" =>"wewe" 
            );
       
            JWT::$leeway = 240; 

            $JWT= JWT::encode($payload,$privateKey,'HS256');
            //El header se autogenera con el algoritmo y tipo de token
            //Tambien se encripta automaticamente en base64url   

            $response->getBody()->write(json_encode($jwt));
            return $response->withHeader('Content-type','application/json');
        }
    }

    public static function retornarListaUsuarios($request,$response,$args){
        $listaUsuarios=Usuarios::buscar_list_usuarios();
        $response->getBody()->write(json_encode($listaUsuarios));
        return $response;
    }

    public static function retornarEstadoRegistro($request,$response,$args){
        $json = $request->getBody();
        $data = json_decode($json,true);
       
        //MEJOR FORMA DE ITERAR EL JSON??
        $usuario=new Usuarios();
        $estadoRegistro=$usuario->registrar_usuario($data['id_usuario'],$data['email'],$data['contraseña']
                                                    ,$data['nombre'],$data['apellido'],$data['tipo_usuario']);
        $response->getBody()->write($estadoRegistro);                                    
        return $response;
    }

    

}

?>