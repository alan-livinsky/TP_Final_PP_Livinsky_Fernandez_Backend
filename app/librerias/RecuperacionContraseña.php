<?php


function validarEnlaceRecuperContraseña($request,$response,$args){

    $selector=$args['selector'];
    $token=$args['token'];

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("SELECT * FROM solicitudes_recuperar_contraseña WHERE selector='$selector'");
    $consulta->execute();
    $consultaSelector=$consulta->fetchAll(PDO::FETCH_ASSOC);

    if($consultaSelector){

        if($token==$consultaSelector['token']){
            $response->withHeader('Location','https://www.geeksforgeeks.org/postgresql-delete/')->withStatus(302);

        }
        else{
            //mandar a pagina de error
            $response->withHeader('Location','https://www.geeksforgeeks.org/postgresql-delete/')->withStatus(302);
        }

    }
    else{
        //mandar a pagina de error
        $response->withHeader('Location','https://www.geeksforgeeks.org/postgresql-delete/')->withStatus(302);
    }

    

    $response->getBody()->write("vamos manaos");
    return $response;

}


function  generarTokenEmailRecuperacion($email){
    
    /*
    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("SELECT * FROM solicitudes_recuperar_contraseña WHERE email_solicitante='$email'");
    $consulta->execute();
    $consultaSelector=$consulta->fetchAll(PDO::FETCH_ASSOC);
    */

    $selector=base64_encode(random_bytes(8));
    $selector=str_replace("/","",$selector);

    $token=base64_encode(random_bytes(32));
    $token=str_replace("/","",$token);

    $selectorMasToken=[
        "selector"=>$selector,
        "token"=>$token
    ];

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fechaHoraActual=date('Y/m/d H:i:s');
    $fechaVencimiento=date('Y/m/d H:i:s',strtotime("$fechaHoraActual +1 day"));

    /*
    $fechaHoraActual=new DateTime();
    echo $fechaHoraActual;
    $fechaVencimiento=$fechaHoraActual->modify('+5 minutes');
    echo $fechacencimiento;
    */

    $accesoDatos=Acceso_a_datos::obtenerConexionBD();
    $consulta=$accesoDatos->prepararConsulta("INSERT INTO solicitudes_recuperar_contraseña 
                                            VALUES
                                            (default,'$email','$selector','$token','$fechaVencimiento')");
    $consulta->execute();

    
    return $selectorMasToken;
}


function prepararEmailDeRecuperacion($email){

    $selectorMasToken=generarTokenEmailRecuperacion($email);

    echo $selectorMasToken;

    $urlRecuperacion="https://tp-final-pp-liv-ferz-backend.herokuapp.com/Usuarios/emailRecuperacion/".$selectorMasToken["selector"]."/".$selectorMasToken["token"];

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

?>