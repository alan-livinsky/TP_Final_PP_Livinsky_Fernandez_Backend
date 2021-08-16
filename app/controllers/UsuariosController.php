<?php

use Firebase\JWT\JWT;//Por algun motivo no toma la dependencia desde el index
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/*
function prepararEmailDeRecuperacion($email){
    
    $token=generarTokenEmailRecuperacion($email);

    $urlRecuperacion="https://tp-final-pp-liv-ferz-backend.herokuapp.com/Usuarios/emailRecuperacion/".$token;

    $contenidoEmail='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                        <tbody>
                            <tr>
                                <td align="center" style="padding:0;">
                                    <table role="presentation"
                                        style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                        <tbody>
                                            <tr>
                                                <td align="center" style="background:#f0b13c;">
                                                    <img src="https://tp-final-pp-liv-ferz-frontend.herokuapp.com/img/CabeceraEmail.png"
                                                        alt="" width="600" style="height:auto;display:block;">
                                                </td>
                                            </tr>
                    
                                            <tr style="height:500px;">
                                                <td style="padding:36px 30px 42px 30px;">
                                                    <table role="presentation"
                                                        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                        <h1 style="color:#153643">SAE-SH</h1>
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding:0 0 36px 0;color:#153643;">
                                                                    <h1
                                                                        style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                                                                        Solicitud de recuperacion de contraseña.</h1>
                                                                    <p
                                                                        style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                                        Le enviamos este correo porque hemos recibido una solicitud de
                                                                        recuperacion de contraseña para esta cuenta de correo electronico.
                                                                    </p>
                                                                    <p
                                                                        style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                                        <a href="'.$urlRecuperacion.'">Recuperar Contraseña</a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                    
                                            <tr>
                                                <td style="padding:30px;background:#f38a28;">
                                                    <table role="presentation"
                                                        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding:0;width:50%;" align="left">
                                                                    <p
                                                                        style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                                                        SAE-SH - Por favor no responda a este correo electronico.</p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                    
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>';
    return $contenidoEmail;
}
*/




//--------------------------------------------------------------------------------------------//

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
    
 /*
    public static function retornarRecuperacionContraseña($request,$response,$args){
        $datosDelUsuario=$request->getBody();
        $datosDelUsuario=json_decode($datosDelUsuario);
        $email=$datosDelUsuario->email;
        //$id=$requestParamter['id'];
        $controlador=new UsuariosController();
        //$estadoEnvio=$controlador->enviarEmailDeRecuperacion($email);
        $response->getBody()->write(Json_encode("sasaas"));
        return $response->withHeader('Content-type','application/json');
    }
*/

    //Use the second parameter of json_decode to make it return an array:
    //$result = json_decode($data, true);
    //function sendVerificationEmail($email,$id)
    
    public static function retornarEmailDeRecuperacion($request,$response,$args){      

        $datosDelUsuario=$request->getBody();
        $datosDelUsuario=json_decode($datosDelUsuario);
        $email=$datosDelUsuario->email;

        SolicitudRecuperacion::generarTokenEmailRecuperacion($email);

        try {
            $mail=new PHPMailer;
            //$mail->SMTPDebug=SMTP::DEBUG_SERVER;                //Enable verbose debug output
            //Por algun motivo genera error de cors
            $mail->isSMTP();                                      //Send using SMTP
            $mail->Host='smtp.gmail.com';                         //Set the SMTP server to send through
            $mail->SMTPAuth=true;                                 //Enable SMTP authentication
            $mail->Username='SAESHitbeltran@gmail.com';           //SMTP username
            $mail->Password='rwbiofucouofrvth';                   //SMTP contraseña de aplicacion (autentificacion en 2 pasos)
            $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;        //Enable implicit TLS encryption
            $mail->Port=465;     

            //Recipients
            $mail->setFrom('SAESHitbeltran@gmail.com','SAE-SH');  //Add a recipient 
            $mail->addAddress($email,'Usuario');                  //Name is optional

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');       //Add attachments
            //$mail->addAttachment('/tmp/image.jpg','new.jpg');   //Optional name

            //Content
            $mail->Subject = 'Recuperacion de acceso a cuenta';

           // generarTokenEmailRecuperacion($email);
            $test=generarTokenEmailRecuperacion($email);
            $mail->Body="a";
        
            $mail->isHTML(true); //Set email format to HTML
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
            
           $response->withHeader('Content-type','application/json');
           $response->withStatus(200);
           return $response;

        }catch (\Exception $e){
            $response->withHeader('Content-type','application/json');
            //No es lo suficientemente representativo.
            $response->withStatus(500);
        }

    }
}

  