<?php

use Firebase\JWT\JWT;

    function retornarListaUsuarios($request,$response,$args){
        $listaUsuarios=buscar_lista_usuarios();
        $response->getBody()->write(json_encode($listaUsuarios));
        return $response;
    }

    function retornarTokenAcceso($request,$response,$args){
        $json=$request->getBody();
        $buscar=json_decode($json,true);
        $usuario=buscar_usuario($buscar['email'],$buscar['password']);

        if($usuario==false){
           return $response ->withStatus(401)
                            ->withHeader('Content-Type','text/html');                       
        }
        else{
            $privateKey=$_ENV['JWT_SECRET'];

            $cursosAsociados=buscarCursosAsociados($usuario[0]["id_usuario"]);

            $payload = array(
                "sub" =>$usuario[0]["id_usuario"],
                "nom" => $usuario[0]["nombre"],
                "ape" => $usuario[0]["apellido"],
                "tu" =>$usuario[0]["tipo_usuario"],
                "cua" =>$cursosAsociados
            );
       
            JWT::$leeway = 240; 
            $token_creado= JWT::encode($payload,$privateKey,'HS256');
            
            //El header se autogenera con el algoritmo y tipo de token
            //Tambien se encripta automaticamente en base64url  
            $response->getBody()->write(json_encode($token_creado));
            return $response->withHeader('Content-type','application/json');
        }
    }

    function retornarEstadoRegistro($request,$response,$args){
        $json= $request->getBody();
        $datos_usuario = json_decode($json,true);

        $estadoRegistro=registrarUsuario($datos_usuario);

        if($estadoRegistro=="Curso inexistente"){
            return $response->withStatus(404);
        }

        //$response->getBody()->write(Json_encode($estadoRegistro));                                    
        //return $response->withHeader('Content-type','application/json');
        return $response;
    }

    function retornarEstadoEliminacionCuenta($request,$response,$args){
        $data=$request->getAttribute("token");

        $estadoactualizacion=eliminar_usuario($data['sub']);

        $response->getBody()->write(Json_encode($estadoactualizacion));                                    
        return $response->withHeader('Content-type','application/json');
    }

    function retornarEstadoActualizacionContraseña($request,$response,$args){
        
        $datosUsuario=$request->getAttribute("token");
        $json_contraseñas=$request->getBody();
        $contraseñas=json_decode($json_contraseñas);

        $validacionDeContraseñaAntigua=buscarUsuarioPorID($datosUsuario['sub'],$contraseñas->antigua);

        if($validacionDeContraseñaAntigua){
            $estadoactualizacion=actualizar_contraseña($datosUsuario['sub'],$contraseñas->nueva,);
            echo $estadoactualizacion;
            $response->getBody()->write(Json_encode($estadoactualizacion));                                    
            return $response->withHeader('Content-type','application/json');
        }
        else{
            return $response>withStatus(401);
        }
    }

    function retornarEstadoRecuperarContraseña($request,$response,$args){
        $json=$request->getBody();
        $json=json_decode($json);
        $selector=$json->selector;
     
        $contraseña=$json->contraseña;
        $contraseña=password_hash($contraseña,PASSWORD_DEFAULT);

        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta("SELECT * FROM solicitudes_recuperar_contraseña WHERE selector='$selector'");
        $consulta->execute();
        $consultaSelector=$consulta->fetchAll(PDO::FETCH_ASSOC);

        if($consultaSelector){
            $email=$consultaSelector[0]['email_solicitante'];
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("SELECT email FROM usuarios WHERE email='$email'");
            $consulta->execute();
            $consultaEmailSolicitante=$consulta->fetchAll(PDO::FETCH_ASSOC);
       
            if($consultaEmailSolicitante){
                $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
                echo $contraseña;

                $consulta=$accesoDatos->prepararConsulta("UPDATE usuarios 
                                                          SET password='$contraseña'
                                                          WHERE email='$email'");
                $consulta->execute();

                //Falta la eliminacion de la solicitud ante recuperacion exitosa.

                $estado="Recuperacion completada";
                $response->getBody()->write(Json_encode($estado));                                    
                return $response->withHeader('Content-type','application/json');
            }
        }

        $estado="Error";
        $response->getBody()->write(Json_encode($estado));                                    
        return $response->withHeader('Content-type','application/json');  
    }