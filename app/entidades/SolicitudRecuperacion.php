<?php

    class SolicitudRecuperacion{

    public static function generarTokenEmailRecuperacion($email){  
        $token=random_bytes(32);
        //$token="pepe";
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaHoraActual=date('Y/m/d H:i:s');
        $fechaVencimiento=date('Y/m/d H:i:s',strtotime("$fechaHoraActual +1 day"));

        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("INSERT INTO solicitudes_recuperar_contraseña 
                                                VALUES
                                                (default,'$email','$token','$fechaVencimiento')");
        $consulta->execute();
    }
    
} 

?>