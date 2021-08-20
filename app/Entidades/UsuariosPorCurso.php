<?php

function asociarAlumnoCurso($año,$comision,$turno,$id_usuario){
    $cursoEncontrado=buscarCurso($año,$comision,$turno);
    $id_curso=$cursoEncontrado[0]["id_curso"];
    var_dump($cursoEncontrado);

    if(count($cursoEncontrado)!=0){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();

        $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios_por_curso
                                                VALUES ($id_usuario,$id_curso)");
        $consulta->execute(); 
    }
}

?>