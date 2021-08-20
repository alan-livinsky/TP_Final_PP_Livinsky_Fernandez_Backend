<?php

 
    function buscar_lista_materiales(){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("SELECT nombre FROM materiales");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function buscarDatosMaterial($nombreMaterial){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("SELECT * FROM materiales WHERE nombre='$nombreMaterial'");
        $consulta->execute(); 
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    
?>