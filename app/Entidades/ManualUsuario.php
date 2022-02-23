<?php

    function buscarManuales($opcion){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("SELECT * FROM explicaciones_aplicacion,ejercicios
                                                  WHERE ejercicios.nombre_ejercicio='$opcion'
                                                  AND explicaciones_aplicacion.id_ejercicio=ejercicios.id_ejercicio");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

?>