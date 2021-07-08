<?php

use Firebase\JWT\JWT;//Por algun motivo no toma la dependencia desde el index
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UsuariosController{

    //Request $request
    public static function retornarUsuario($request,$response,$args){
        $usuario=Usuarios::buscar_usuario($args['usuario'],$args['contrasea']);
        if ($usuario){
            
            $response->getBody()->write(json_encode($usuario));
            return $response->withHeader('Content-type','application/json');
        } 
        else {
            return $response->withStatus(401);
        }
    }

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
    
 
    public static function retornarRecContraseña($request,$response,$args){
        $datosDelUsuario=$request->getBody();
        $datosDelUsuario=json_decode($datosDelUsuario);
        $email=$datosDelUsuario->email;
        //$id=$requestParamter['id'];
        $controlador=new UsuariosController();
        $response=$controlador->enviarEmailDeRecuperacion($email);
        return $response;
    }

    //Use the second parameter of json_decode to make it return an array:
    //$result = json_decode($data, true);


    //function sendVerificationEmail($email,$id)
    //Parametro de contenido???
    public function enviarEmailDeRecuperacion($email)
    {      
        $mail = new PHPMailer;
        $mail->SMTPDebug=3;
        $mail->isSMTP();
        $mail->Host="smtp.gmail.com";
        $mail->Port=587;
        $mail->SMTPSecure="tls";
        $mail->SMTPAuth=true;
        $mail->Username="SAESHitbeltran@gmail.com";
        $mail->Password="SAESHlivfer";

        $mail->addAddress($email,"User Name");
        $mail->Subject="Verify Your Email Address For StackOverFlow";
        $mail->isHTML();
        $mail->Body=" Welcome to StackOverFlow.<b><b> Please verify your email adress to continue..";
        $mail->From="SAESHitbeltran@gmail.com";
        $mail->FromName="Social Codia";

        if($mail->send()){
            return "Email Has Been Sent Your Email Address";
        }
        else{
            return "Failed To Sent An Email To Your Email Address";
        }
    }
    
}
?>