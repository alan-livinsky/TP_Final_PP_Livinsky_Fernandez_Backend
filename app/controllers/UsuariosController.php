<?php

use Firebase\JWT\JWT;//Por algun motivo no toma la dependencia desde el index
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UsuariosController{

    public static function retornarListaUsuarios($request,$response,$args){
        $listaUsuarios=Usuarios::buscar_lista_usuarios();
        $response->getBody()->write(json_encode($listaUsuarios));
        return $response;
    }

    public static function retornarTokenAcceso($request,$response,$args){
        $json = $request->getBody();
        $buscar = json_decode($json,true);
        $usuario=Usuarios::buscar_usuario($buscar['email'],$buscar['contraseña']);
        
        if($usuario==false){
           return $response
                        ->withStatus(401)
                        ->withHeader('Content-Type', 'text/html');
        }
        else{
            $privateKey=$_ENV['JWT_SECRET'];

            $payload = array(
                "email" =>$usuario[0]->email,//INSEGURO
                "nom" => $usuario[0]->nombre,
                "ape" => $usuario[0]->apellido,
                "tu" =>$usuario[0]->tipo_usuario
            );
       
            JWT::$leeway = 240; 
            $token_creado= JWT::encode($payload,$privateKey,'HS256');
            
            //El header se autogenera con el algoritmo y tipo de token
            //Tambien se encripta automaticamente en base64url  
            $response->getBody()->write(json_encode($token_creado));
            return $response->withHeader('Content-type','application/json');
        }
    }


    public static function retornarEstadoRegistro($request,$response,$args){
        $json= $request->getBody();
        $datos_usuario = json_decode($json,true);

        $usuario=new Usuarios();
        $estadoRegistro=$usuario->registrar_usuario($datos_usuario);

        $response->getBody()->write(Json_encode($estadoRegistro));                                    
        return $response->withHeader('Content-type','application/json');
    }

    public static function retornarEstadoEliminacionC($request,$response,$args){
        $data=$request->getAttribute("token");

        $usuario=new Usuarios();
        $estadoactualizacion=$usuario->eliminar_usuario($data['email']);

        $response->getBody()->write(Json_encode($estadoactualizacion));                                    
        return $response->withHeader('Content-type','application/json');
    }

    public static function retornarEstadoActualizacionContraseña($request,$response,$args){
        $json_contraseñas=$request->getBody();
        $json_contraseñas=json_decode($json_contraseñas);
        
        $data=$request->getAttribute("token");

        $usuario=new Usuarios();
        $estadoactualizacion=$usuario->actualizar_contraseña($data['email'],$json_contraseñas->nueva,);

        $response->getBody()->write(Json_encode($estadoactualizacion));                                    
        return $response->withHeader('Content-type','application/json');
    }

    public static function retornarEstadoRecuperarContraseña($request,$response,$args){
        $json=$request->getBody();
        $json=json_decode($json);
        $selector=$json->s;
        $contraseña=$json->contraseña;

        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta("SELECT * FROM solicitudes_recuperar_contraseña WHERE selector='$selector'");
        $consulta->execute();
        $consultaSelector=$consulta->fetchAll(PDO::FETCH_ASSOC);

        if($consultaSelector){
            $email=$consultaSelector['email_solicitante'];
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("SELECT email FROM usuarios WHERE email='$email'");
            $consulta->execute();
            $consultaEmailSolicitante=$consulta->fetchAll(PDO::FETCH_ASSOC);
            
            if($consultaEmailSolicitante){
                $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
                $consulta=$accesoDatos->prepararConsulta("UPDATE usuarios 
                                                          SET contraseña='$contraseña'
                                                          WHERE email='$email'");
                $consulta->execute();

                $estado="Actualizacion completada";
                $response->getBody()->write(Json_encode($estado));                                    
                return $response->withHeader('Content-type','application/json');
            }
        }

        $estado="Error";
        $response->getBody()->write(Json_encode($estado));                                    
        return $response->withHeader('Content-type','application/json');  
    }

    public static function retornarEmailDeRecuperacion($request,$response,$args){      

        $datosDelUsuario=$request->getBody();
        $datosDelUsuario=json_decode($datosDelUsuario);
        $email=$datosDelUsuario->email;

        $contenidoEmailRecuperacion=prepararEmailDeRecuperacion($email);
        echo $contenidoEmailRecuperacion;

        if($contenidoEmailRecuperacion=="Solicitud existente"){
            echo "pepe";
            return $response->withStatus(409);
        }

        try {
            $mail=new PHPMailer;
            //$mail->SMTPDebug=SMTP::DEBUG_SERVER;                
            //Por algun motivo genera error de cors
            $mail->isSMTP();                                    
            $mail->Host='smtp.gmail.com';                       
            $mail->SMTPAuth=true;                                 
            $mail->Username='SAESHitbeltran@gmail.com';         
            $mail->Password='rwbiofucouofrvth';           //SMTP contraseña de aplicacion (autentificacion en 2 pasos)
            $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;      
            $mail->Port=465;     
            //Recipients
            $mail->setFrom('SAESHitbeltran@gmail.com','SAE-SH'); 
            $mail->addAddress($email,'Usuario');                 
            //Content
            $mail->Subject = 'Recuperacion de acceso a cuenta';
            $mail->Body=$contenidoEmailRecuperacion;
            $mail->isHTML(true);
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
            
           return $response->withStatus(200);

        }catch (\Exception $e){
            //No es lo suficientemente representativo.
            return $response->withStatus(500);
        }

    }
}

  