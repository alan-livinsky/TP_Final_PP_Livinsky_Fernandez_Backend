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
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                //Enable verbose debug output
        $mail->isSMTP();                                      //Send using SMTP
        $mail->Host= 'smtp.gmail.com';               //Set the SMTP server to send through
        $mail->SMTPAuth=true;                             //Enable SMTP authentication
        $mail->Username='SAESHitbeltran@gmail.com';               //SMTP username
        $mail->Password='rwbiofucouofrvth';  //SMTP contraseña de aplicacion (autentificacion en 2 pasos)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      //Enable implicit TLS encryption
        $mail->Port       = 465;     

        //Recipients
        $mail->setFrom('SAESHitbeltran@gmail.com','SAE-SH'); //Add a recipient 
        $mail->addAddress($email,'Usuario');     //Name is optional

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->Subject = 'Here is the subject';
        //$mail->Body='This is the HTML message body <b>in bold!</b>';
        $mail->Body=readfile('email.html');
        
        
        /*'<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <link rel="stylesheet" href="style.css">
            <title>Email</title>
        </head>
        
        
        <body>


    <main class="container">
        <div class="logo">
                <img src="https://image.e.mozilla.org/lib/fe9915707361037e75/m/3/firefox-logo-2x-sept2017.png" class="logo">
        </div>
        
        <img src="https://image.e.mozilla.org/lib/fe9915707361037e75/m/4/afb835a0-c1f1-4c87-b762-8ec6700acba1.jpg" class="header-img">

        <div class="body">
            <h2>Enjoy the silence</h2>
            
            <p> Websites that autoplay video can be super annoying. You didnt select the video to play &mdash; it was started for you. <i>Hurumph!</i> Firefox will now stop that from happening, putting you in control. If youd like to hear or see a video, just click on the play button to watch it.</p>

            <p>Firefox with <a>Block Autoplay</a>: the way online video should be</p>
            
            <button>FIND OUT MORE</button>

        </div>

    </main>

    <footer class="container">
        <p>Thanks for reading!</p>
        <p>Youre receiving this email because we think you’re neat, AND you subscribed to hear from us. If our emails aren’t sparking joy, we’ll understand if you  <a>unsubscribe.</a></p>
    
        <p>You can also <a href="#">update your email preferences</a> at anytime.</p>


        <img src="https://image.e.mozilla.org/lib/fe9915707361037e75/m/3/Mozilla-Logo-2017.png">
        
        <div>
            <a>Donate to Mozilla</a> <span>|</span> <a>Download Firefox</a>
            <p>331 E. Evelyn Avenue Mountain View CA 94041</p>
            <P><a>Legal</a> <span>•</span> <a>Privacy</a></P>
        </div>

    </footer>

</div>

    
</body>
</html>';*/
        
$mail->isHTML(true);                                  //Set email format to HTML
        
        
        
        
        
        
        
        
        
        
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        
    

        if($mail->send()){
            $response="Email Has Been Sent Your Email Address";
            return $response;
        }
        else{
            $response="Failed To Sent An Email To Your Email Address";
            return $response;
        }
    }
    
}
?>