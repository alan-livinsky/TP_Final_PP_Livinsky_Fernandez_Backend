<?php

    function buscar_opciones_profesor(){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta("SELECT * FROM opciones_profesor");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

?>