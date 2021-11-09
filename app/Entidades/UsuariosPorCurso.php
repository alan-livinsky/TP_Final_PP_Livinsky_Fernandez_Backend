<?php

function asociarAlumnoCurso($año,$comision,$turno,$id_usuario){
    $cursoEncontrado=buscarCurso($año,$comision,$turno);
    $id_curso=$cursoEncontrado[0]["id_curso"];

    if(count($cursoEncontrado)!=0){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD();

        $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios_por_curso
                                                VALUES ($id_usuario,$id_curso)");
        $consulta->execute(); 
        return "Exito";
    }
    else{
        return "Error";
    }
}

function asociarProfesorCurso($insertPreparado){
    $accesoDatos=Acceso_a_datos::obtenerConexionBD();
    $consulta=$accesoDatos->prepararConsulta("$insertPreparado");
    $consulta->execute(); 
    return "Exito";
}


/*USADO EN ACCESO A PAGINAS*/
function buscarCursosAsociados($id_usuario){
    $accesoDatos = Acceso_a_datos::obtenerConexionBD();
    $consulta = $accesoDatos->prepararConsulta("SELECT id_curso
                                                FROM usuarios_por_curso
                                                WHERE id_usuario=$id_usuario");
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}



//PREPARAR MANEJO DE DATOS FALTANTE

//:RETORNA UNA <<ID_CURSO>>
function buscarCursoAlumno($id_usuario){
    $accesoDatos = Acceso_a_datos::obtenerConexionBD();
    $consulta = $accesoDatos->prepararConsulta("SELECT usuarios_por_curso.id_curso 
                                                FROM usuarios,usuarios_por_curso
                                                WHERE usuarios.id_usuario=usuarios_por_curso.id_usuario
                                                AND usuarios.id_usuario=$id_usuario");
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

//:RETORNA LISTA DE PROFESORES ASOCIADOS A UN CURSO
function buscarProfesoresAsociadosACurso($id_usuario,$id_curso){
    $accesoDatos = Acceso_a_datos::obtenerConexionBD();
    $consulta = $accesoDatos->prepararConsulta("SELECT usuarios.id_usuario 
                                                FROM usuarios,usuarios_por_curso
                                                WHERE usuarios_por_curso.id_curso=$id_curso
                                                and usuarios_por_curso.id_usuario=$id_usuario
                                                and usuarios.tipo_usuario='Profesor'");
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}












?>