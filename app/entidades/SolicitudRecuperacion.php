<?php

    class SolicitudRecuperacion{

    public static function generarTokenEmailRecuperacion($email){  
        $token=base64_encode(random_bytes(32));
        echo $token;
        //$token="pepe";
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaHoraActual=date('Y/m/d H:i:s');
        $fechaVencimiento=date('Y/m/d H:i:s',strtotime("$fechaHoraActual +1 day"));

        

        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("INSERT INTO solicitudes_recuperar_contraseña 
                                                VALUES
                                                (default,'$email','$fechaVencimiento','$token')");
        $consulta->execute();
    }
    
} 

?>