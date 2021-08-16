<?php

function prepararEmailDeRecuperacion($token){     

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


function generarTokenEmailRecuperacion($email){  
    $token=random_bytes(32);
    date_default_timezone_set('Argentina/Buenos_Aires');
    $fechaHoraActual=date('Y/m/d H:i:s');
    $fechaVencimiento=date('Y/m/d H:i:s',strtotime("$fechaHoraActual +1 day"));

    $datosToken = [
        token_recuperacion=>$token,
    ];

    altaSolicitudRestaurarContraseña($email,$token,$fechaVencimiento);

    return $token;

}   

function altaSolicitudRestaurarContraseña($email,$token,$fechaVencimiento){
    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("INSERT INTO solicitudes_recuperar_contraseña 
                                            values
                                            (default,'$email','$token','$fechaVencimiento')");
    $consulta->execute();
}

?>