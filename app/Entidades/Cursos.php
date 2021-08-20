<?php


    function buscarlistaDeCursos(){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("SELECT * FROM cursos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    function buscarCurso($año,$comision,$turno){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("SELECT id_curso FROM cursos Where año='$año' and comision='$comision' and turno='$turno'");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

?>