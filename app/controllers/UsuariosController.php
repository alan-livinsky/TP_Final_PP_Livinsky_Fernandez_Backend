<?php

use Firebase\JWT\JWT;

class UsuariosController{

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
        //var_dump($json);

        $buscar = json_decode($json,true);

        //var_dump($buscar);

        //var_dump($buscar['email']);

        $usuario=Usuarios::buscar_usuario($buscar['email'],$buscar['contraseña']);
        
        if($usuario==false){
           return $response
                        ->withStatus(401)
                        ->withHeader('Content-Type', 'text/html');
        }
        else{
            $privateKey = $_ENV['JWT_SECRET'];

            $payload = array(
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
        $json = $request->getBody();
        $data = json_decode($json,true);
       
        $usuario=new Usuarios();
        $estadoRegistro=$usuario->registrar_usuario($data['id_usuario'],$data['email'],$data['contraseña']
                                                    ,$data['nombre'],$data['apellido'],$data['tipo_usuario']);

        $response->getBody()->write(Json_encode($estadoRegistro));                                    
        return $response->withHeader('Content-type','application/json');
    }
    
    /*
    public static function retornarRecContraseña($request,$response,$args){
        $requestParamter = $request->getParsedBody();
        $email =  $requestParamter['email'];
        $id = $requestParamter['id'];
        sendVerificationEmail($email,$id);
    }

    //Function to send mail, 
    function sendVerificationEmail($email,$id)
    {      
        $mail = new PHPMailer;
            $mail->SMTPDebug=3;
            $mail->isSMTP();
            $mail->Host="smtp.gmail.com";
            $mail->Port=587;
            $mail->SMTPSecure="tls";
            $mail->SMTPAuth=true;
            $mail->Username="socialcodia@gmail.com";
            $mail->Password="12345";

            $mail->addAddress($email,"User Name");
            $mail->Subject="Verify Your Email Address For StackOverFlow";
            $mail->isHTML();
            $mail->Body=" Welcome to StackOverFlow.<b><b> Please verify your email adress to continue..";
            $mail->From="SocialCodia@gmail.com";
            $mail->FromName="Social Codia";

            if($mail->send())
            {
                echo "Email Has Been Sent Your Email Address";
            }
            else
            {
            echo "Failed To Sent An Email To Your Email Address";
            }
    }
    */

}
?>