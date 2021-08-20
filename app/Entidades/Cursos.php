<?php


    function buscarlistaDeCursos(){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();
        $consulta=$accesoDatos->prepararConsulta("SELECT 
                                                    ARRAY(SELECT DISTINCT(año) FROM cursos ORDER BY año) AS col1_values,
                                                    ARRAY(SELECT DISTINCT(comision) FROM cursos ORDER BY comision) AS col2_values,
                                                    ARRAY(SELECT DISTINCT(turno) FROM cursos) AS col3_values");
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