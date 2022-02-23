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

    function cargarNuevoMaterial($material){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        
        $nombre=$material->nombre;
        $riesgo=$material->riesgo;
        $poder_calorifico=$material->poder_calorifico;
        $fuente_de_informacion=$material->fuente_de_informacion;

        $consulta=$accesoDatos->prepararConsulta("SELECT max(id_material) FROM materiales");
        $consulta->execute();
        $ultimaId=$consulta->fetchAll(PDO::FETCH_ASSOC);

         $id_material=($ultimaId[0]['max'])+1;

        $consulta=$accesoDatos->prepararConsulta("INSERT INTO materiales
                                                  VALUES
                                                  ($id_material,'$nombre','$riesgo',$poder_calorifico,'$fuente_de_informacion')");
        $consulta->execute();

        return $estadoCreacion="200";
    }

?>