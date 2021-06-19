<?php

class UsuariosController{

    public static function retornarUsuario($request,$response,$args){
        $usuario=Usuarios::buscar_usuario($args['usuario'],$args['contrasea']);
        $response->getBody()->write(json_encode($usuario));
        return $response->withHeader('Content-type','application/json');
    }

    public static function retornarListaUsuarios($request,$response,$args){
        $listaUsuarios=Usuarios::buscar_list_usuarios();
        $response->getBody()->write(json_encode($listaUsuarios));
        return $response;
    }

    public static function retornarEstadoRegistro($request,$response,$args){
        $json = $request->getBody();
        /*$data = json_decode($json, true);
        $response->getBody()->write(json_encode($data));
        return $response;*/

        $estadoRegistro=Usuarios::registrar_usuario($args['id_usuario'],$args['email'],$args['contraseña'],
                                            $args['nombre'],$args['apellido'],$args['tipo_usuario']);
        $response->getBody()->write($estadoRegistro);                                    
        return $response;
    }

}

?>