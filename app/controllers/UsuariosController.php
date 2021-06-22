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

    public static function retornarEstadoRegistro(Request $request,$response,$args){
        $json = $request->getBody();
        $data = json_decode($json,true);
        /*$data = json_decode($json, true);
        $response->getBody()->write(json_encode($data));
        return $response;*/

        //MEJOR FORMA DE ITERAR EL JSON??
        $usuario=new Usuarios();
        $estadoRegistro=$usuario->registrar_usuario($data['id_usuario'],$data['email'],$data['contraseña']
                                                    ,$data['nombre'],$data['apellido'],$data['tipo_usuario']);
        $response->getBody()->write($estadoRegistro);                                    
        return $response;
    }

}

?>