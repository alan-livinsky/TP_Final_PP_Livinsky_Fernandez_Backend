<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmailDeRecuperacion($request,$response,$args){ 

    //Se obtiene el email ingresado cuando se solicito la recuperacion de contraseña
    $emailDelSolicitante=$request->getBody();
    $emailDelSolicitante=json_decode($emailDelSolicitante);
    $email=$emailDelSolicitante->email;

    //Llamado a la funcion encargada de generar los tokens de seguridad
    $tokensGenerados=generarTokenEmailRecuperacion($email);

    eliminacionSimple("solicitudes_recuperar_contraseña","vencimiento"/*,"<","now()"*/);

    //Si no se retornan tokens se procede a retornar que ya existe una solicitud vigente
    if(!$tokensGenerados){
        //Se retorna la respuesta a la peticion.
        return $response->withStatus(409);
    }

    //Si se retornaron tokens se procede al llamado a la funcion que prepara el contenido del email de recuperacion.
    $contenidoEmailRecuperacion=prepararEmailDeRecuperacion($tokensGenerados);

    //Se procede a configurar el Email que se enviara al usuario.
    try {
        $mail=new PHPMailer;
        //$mail->SMTPDebug=SMTP::DEBUG_SERVER;                
        //Por algun motivo genera error de cors
        $mail->isSMTP();                                    
        $mail->Host='smtp.gmail.com';                       
        $mail->SMTPAuth=true;                                 
        $mail->Username='SAESHitbeltran@gmail.com';         
        $mail->Password='rwbiofucouofrvth';            //SMTP contraseña de aplicacion (autentificacion en 2 pasos)
        $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;      
        $mail->Port=465;     
        //Recipientes
        $mail->setFrom('SAESHitbeltran@gmail.com','SAE-SH'); 
        $mail->addAddress($email,'Usuario');                 
        //Contenido
        $mail->Subject = 'Recuperacion de acceso a cuenta';
        $mail->Body=$contenidoEmailRecuperacion;
        $mail->isHTML(true);
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        
        return $response->withStatus(200);

    }catch (\Exception $e){
        return $response->withStatus(500);
    }
}

function validarEnlaceRecuperContraseña($request,$response,$args){

    //Se dispara la eliminacion de vencimientos de solicitudes
    //TEMPORAL DESCONOCEMOS MEJOR FORMA DE HACERLO
    eliminacionSimple("solicitudes_recuperar_contraseña","vencimiento"/*,"<","now()"*/);

    //Se recuperan los tokens que llegaron a la ruta
    $selector=$args['selector'];
    $token=$args['token'];

    //Se valida el selector
    $ConsultaDeSolicitudVigente=busquedaSimple("solicitudes_recuperar_contraseña","selector",$selector);

    //Se verifica si se obtuvieron datos a partir de la consulta realizada
    if($ConsultaDeSolicitudVigente){

        //Se prodece a evaluar los datos obtenidos validando el token
        //Si el token que llego como parametro coincide con el token almacenado se deriva a la pagina de recuperacion
        if($token==$ConsultaDeSolicitudVigente[0]['token']){
            return $response->withHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/Recuperar_Contraseña.html?s='.$selector)->withStatus(302);
        }
        else{
            //Si el token no coincide se redirecciona a la pagina de error
            return $response->withHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/Error.html')->withStatus(302);
        }
    }
    else{
        //Si no se obtuvieron datos asociados al selector provisto se redirecciona a la pagina de error
        return $response->withHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/Error.html')->withStatus(302);
    }

}

function busquedaSimple($tabla,$campoCondicion,$dato){

    //ACA IRIA UN FILTRO POR TIPO DE DATO

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("SELECT * FROM $tabla WHERE $campoCondicion='$dato'");
    $consulta->execute();
    $resultadoConsulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
    return $resultadoConsulta;
}

function eliminacionSimple($tabla,$campo/*,$condicion,$dato*/){

    //ACA IRIA UN FILTRO POR TIPO DE DATO

    //$condicion=$campo.''.$condicion.''.$dato;

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $date=date('Y-m-d H:i:s');

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("DELETE FROM $tabla WHERE vencimiento<'$date'");
    //echo "DELETE FROM $tabla WHERE $condicion";
    $consulta->execute();
}

function  generarTokenEmailRecuperacion($email){

    //Se realiza una consulta para verificar si ya existe otra solicitud de recuperacion de contraseña para el email provisto
    //Se busca evitar duplicados
    $consultaDeSolicitudVigente=busquedaSimple("solicitudes_recuperar_contraseña","email_solicitante",$email);

    //Si se obtiene un resultado de la consulta se retorna tokens vacios
    if($consultaDeSolicitudVigente){
        return $tokensGenerados=[];
    }

    //Si no se obtiene un resultado de la consulta se procede a generar los tokens
    $selector=base64_encode(random_bytes(8));
    $selector=str_replace("/","",$selector);

    $token=base64_encode(random_bytes(32));
    $token=str_replace("/","",$token);

    $tokensGenerados=[
        "selector"=>$selector,
        "token"=>$token
    ];

    //Se establece la fecha de vencimiento del token en x cantidad de tiempo a partir de la fecha actual.
    $fechaHoraActual=new DateTime();
    $zonaHoraria=new DateTimeZone('America/Argentina/Buenos_Aires');
    $fechaHoraActual->setTimezone($zonaHoraria);

    $fechaVencimiento=$fechaHoraActual->modify('+10 minutes');
    $fechaVencimiento=$fechaVencimiento->format('Y-m-d H:i:s');

    //Se procede a dar de alta la solicitud en la base de datos
    $accesoDatos=Acceso_a_datos::obtenerConexionBD();
    $consulta=$accesoDatos->prepararConsulta("INSERT INTO solicitudes_recuperar_contraseña 
                                            VALUES
                                            (default,'$email','$selector','$token','$fechaVencimiento')");
    $consulta->execute();

    //Se retornan los token generados
    return $tokensGenerados;
}

function prepararEmailDeRecuperacion($tokensGenerados){

    //Se prepara el enlace que se encontra dentro del email el cual permitira el acceso a la pagina de recuperacion de contraseña
    $urlRecuperacion="https://tp-final-pp-liv-ferz-backend.herokuapp.com/Usuarios/emailRecuperacion/".$tokensGenerados["selector"]."/".$tokensGenerados["token"];

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
                                                                        Por favor utilice el enlace provisto a continuacion para restaurar su contraseña.
                                                                        El mismo tendra una validez de 24hs.
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

?>