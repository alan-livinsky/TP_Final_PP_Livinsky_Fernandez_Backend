<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmailDeRecuperacion($request,$response,$args){ 

    $emailDelSolicitante=$request->getBody();
    $emailDelSolicitante=json_decode($emailDelSolicitante);
    $email=$emailDelSolicitante->email;

    $fechaActual=obtenerFechaActual();

    eliminacionSimple("solicitudes_recuperar_contraseña","vencimiento","<",$fechaActual,"String");

    $tokensDeSeguridad=generarTokensEmailRecuperacion($email);

    //Si no se recibieron tokens se procede a retornar debido a  que ya existe una solicitud vigente
    if(!$tokensDeSeguridad){
        return $response->withStatus(409);//409 - The HTTP 409 Conflict response status code indicates a request conflict with current state of the target resource.
    }

    $contenidoEmailRecuperacion=prepararEmailDeRecuperacion($tokensDeSeguridad);

    //Se procede a configurar el Email que se enviara al usuario.
    //Tal vez Reutilizable en un futuro
    try {
        $mail=new PHPMailer;
        //$mail->SMTPDebug=SMTP::DEBUG_SERVER; 
        //Habilitar en caso de comportamiento inesperado del envio de email para debuguear
        $mail->isSMTP();                                    
        $mail->Host='smtp.gmail.com';                       
        $mail->SMTPAuth=true;                                 
        $mail->Username='SAESHitbeltran@gmail.com';         
        $mail->Password='lanlmetythogahgl'; //SMTP contraseña de aplicacion (autentificacion en 2 pasos)
        $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;      
        $mail->Port=465;     
        //Recipientes
        $mail->setFrom('SAESHitbeltran@gmail.com','SAE-SH'); 
        $mail->addAddress($email,'Usuario');                 
        //Contenido
        $mail->Subject = utf8_decode('Recuperación de acceso a cuenta.'); //El decode es para que tome el tilde.
        $mail->CharSet = 'UTF-8';
        $mail->Body=$contenidoEmailRecuperacion;
        $mail->isHTML(true);
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        
        return $response->withStatus(200);

    }catch (\Exception $e){
        return $response->withStatus(500);
    }
}

function validarEnlaceRecContraseña($request,$response,$args){

    //Se dispara la eliminacion de vencimientos de solicitudes - TEMPORAL DESCONOCEMOS MEJOR FORMA DE HACERLO
    $fechaActual=obtenerFechaActual();
    eliminacionSimple("solicitudes_recuperar_contraseña","vencimiento","<",$fechaActual,"String");

    //Se recuperan los tokens que llegaron a la ruta
    $selector=$args['selector'];
    $token=$args['token'];

    $ConsultaDeSolicitudVigente=busquedaSimple("solicitudes_recuperar_contraseña","selector",$selector);

    if($ConsultaDeSolicitudVigente){
        if($token==$ConsultaDeSolicitudVigente[0]['token']){
            return $response->withHeader('Location','https://final-pp-liv-ferz-frontend.herokuapp.com/Recuperar_Contraseña.html?s='.$selector)->withStatus(302);
        }
        else{
            return $response->withHeader('Location','https://final-pp-liv-ferz-frontend.herokuapp.com/Vencimiento Recuperacion.html')->withStatus(302);
        }
    }
    else{
        return $response->withHeader('Location','https://final-pp-liv-ferz-frontend.herokuapp.com/Vencimiento Recuperacion.html')->withStatus(302);
    }

}

function generarTokensEmailRecuperacion($email){

    $consultaDeSolicitudVigente=busquedaSimple("solicitudes_recuperar_contraseña","email_solicitante",$email);

    if($consultaDeSolicitudVigente){
        return $tokensDeSeguridad=[];
    }

    //Si no se obtiene un resultado de la consulta se procede a generar los tokens

    //$token = bin2hex(random_bytes($length)); // bin2hex output is url safe.
    $selector= bin2hex(random_bytes(8));
    //$selector=base64_encode(random_bytes(8));
    //$selector=str_replace("/","",$selector);

    $token=bin2hex(random_bytes(32));
    //$token=base64_encode(random_bytes(32));
    //$token=str_replace("/","",$token);

    $tokensDeSeguridad=[
        "selector"=>$selector,
        "token"=>$token
    ];

    //Se establece la fecha de vencimiento del token en x cantidad de tiempo a partir de la fecha actual.
    $fechaHoraActual=new DateTime();
    $zonaHoraria=new DateTimeZone('America/Argentina/Buenos_Aires');
    $fechaHoraActual->setTimezone($zonaHoraria);

    $fechaVencimiento=$fechaHoraActual->modify('+3 minutes');
    $fechaVencimiento=$fechaVencimiento->format('Y-m-d H:i:s');


    $accesoDatos=Acceso_a_datos::obtenerConexionBD();
    $consulta=$accesoDatos->prepararConsulta("INSERT INTO solicitudes_recuperar_contraseña 
                                              VALUES
                                              (default,'$email','$selector','$token','$fechaVencimiento')");
    $consulta->execute();

    $consulta=null;

    return $tokensDeSeguridad;
}

function prepararEmailDeRecuperacion($tokensDeSeguridad){

    //Se prepara el enlace que se encontra dentro del email el cual permitira el acceso a la pagina de recuperacion de contraseña
    $urlRecuperacion="https://tp-final-pp-liv-ferz-backend.herokuapp.com/Usuarios/emailRecuperacion/".$tokensDeSeguridad["selector"]."/".$tokensDeSeguridad["token"];

    $contenidoEmail='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                        <tbody>
                            <tr>
                                <td align="center" style="padding:0;">
                                    <table role="presentation"
                                        style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                        <tbody>
                                            <tr>
                                                <td align="center" style="background:#f0b13c;">
                                                    <img src="https://final-pp-liv-ferz-frontend.herokuapp.com/img/Email/CabeceraEmail.png"
                                                        alt="" width="600" style="height:auto;display:block;">
                                                </td>
                                            </tr>
                    
                                            <tr style="height:500px;">
                                                <td style="padding:36px 30px 42px 30px;">
                                                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding:0 0 36px 0;color:#153643;">
                                                                    <h1
                                                                        style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                                                                        Solicitud de recuperación de contraseña.</h1>
                                                                    <p
                                                                        style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                                        Le enviamos este correo porque hemos recibido una solicitud de
                                                                        recuperación de contraseña para esta cuenta de correo electrónico.
                                                                        Por favor utilice el enlace provisto a continuación para restaurar su contraseña.
                                                                        El mismo tendrá una validez de 24hs.
                                                                    </p>
                                                                    <p
                                                                        style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                                        <a href="'.$urlRecuperacion.'">Recuperar Contraseña.</a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                    
                                            <tr>
                                                <td style="padding:30px;background:black;">
                                                    <table role="presentation"
                                                        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding:0;width:50%;" align="left">
                                                                    <p
                                                                        style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:orange;">
                                                                        SAE-SH - Por favor no responda a este correo electrónico.</p>
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

?>