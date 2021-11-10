<?php

//BUSCAR LA LISTA DE NOMBRES DE TODOS LOS CONCEPTOS Y FORMULAS EXISTENTES;
//CREADOS POR EL USUARIO LOGUEADO O PERTENECIENTES A TEORIA SISTEMA.
    function buscarListaGeneralDeTitulos($id_usuario){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT titulo FROM teoria_sistema
                                                    UNION
                                                    SELECT titulo FROM teoria_cursos
                                                    WHERE teoria_cursos.id_usuario=$id_usuario
                                                    ORDER BY titulo ASC");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

//BUSCA LA TEORIA QUE EL USUARIO DESEA EDITAR
    function buscarTeoriaAEditar($titulo,$id_usuario){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        //Primero se verifica si existe una teoria creada por el usuario asociada al titulo seleccionado.
        $consulta=buscarTeoriaCreadaProfesor($titulo,$id_usuario,$accesoDatos);
         
        if($consulta->rowCount()>0){
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            //Si dicha teoria no existe se procede a cargar el equivalente de teoria sistema.
            $consulta=null;
            $consulta=buscarTeoriaSistema($titulo,$id_usuario,$accesoDatos);

            if($consulta->rowCount()>0){
                return $consulta->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                return "Ocurrio un error";
            }
        }    
    }

//BUSCA UNA TEORIA PARTICULAR CREADA POR UN PROFESOR PARTICULAR
    function buscarTeoriaCreadaProfesor($titulo,$id_usuario,$accesoDatos){
        $consulta = $accesoDatos->prepararConsulta("SELECT * FROM teoria_cursos
                                                    WHERE teoria_cursos.titulo='$titulo'
                                                    AND teoria_cursos.id_usuario=$id_usuario");
        $consulta->execute();
        return $consulta;
    }

//BUSCA UN TEORIA PARTICULAR DE TEORIA_SISTEMA
function buscarTeoriaSistema($titulo,$id_usuario,$accesoDatos){
    $consulta = $accesoDatos->prepararConsulta("SELECT * FROM teoria_sistema
                                                WHERE teoria_sistema.titulo='$titulo'");
    $consulta->execute();
    return $consulta;
}

//CREAR TEORIA CURSO
function crearTeoriaCurso($teoria){
    $accesoDatos = Acceso_a_datos::obtenerConexionBD();

    $id_teoria=$teoria["id_teoria"];
    $id_ejercicio=$teoria["id_ejercicio"];
    $id_usuario=$teoria["id_usuario"];
    $titulo=$teoria["titulo"]; 
    $contenido=$teoria["contenido"];
    $tipo=$teoria["tipo"];
    $lista_cursos=json_encode($teoria["lista_cursos"]);

    $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_cursos
                                                VALUES
                                                ($id_teoria,$id_usuario,$id_ejercicio,'$titulo','$contenido','$tipo','$lista_cursos')");
    $consulta->execute();
    return $consulta;
}

//ACTUALIZAR CONTENIDO TEORIA CURSO
function actualizarContenidoTeoriaCursos($datosTeoriaEditar){

    $accesoDatos = Acceso_a_datos::obtenerConexionBD();

    $id_teoria=$datosTeoriaEditar['id_teoria'];
    $id_usuario=$datosTeoriaEditar['id_usuario'];
    $id_ejercicio=$datosTeoriaEditar['id_ejercicio'];
    $titulo=$datosTeoriaEditar['titulo'];
    $contenido=$datosTeoriaEditar['contenido'];
    $tipo=$datosTeoriaEditar['tipo'];
    $lista_cursos=json_encode($datosTeoriaEditar['lista_cursos']);

    $consulta=buscarTeoriaCreadaProfesor($titulo,$id_usuario,$accesoDatos);

    if($consulta->rowCount()<=0){
        $id_teoria='default';
        $consulta=null;
        $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_cursos
                                                    VALUES
                                                    ($id_teoria,$id_usuario,$id_ejercicio,'$titulo','$contenido','$tipo','$lista_cursos')");
        $consulta->execute();
        return $consulta;
    }
    else if($consulta->rowCount()>0){
        $consulta=null;
        $consulta = $accesoDatos->prepararConsulta("UPDATE teoria_cursos
                                                    SET contenido='$contenido' 
                                                    WHERE titulo='$titulo'
                                                    AND id_usuario=$id_usuario");
        $consulta->execute();
        return $consulta;
    }
   
}


function buscarListaOpcionesBarraApoyo($id_usuario,$id_ejercicio){

    //LAS SIGUIENTES FUNCIONES SE ENCUENTRAN EN UsuariosPorCurso.php
    $curso=buscarCursoAlumno($id_usuario);
    
    $id_curso=$curso[0]['id_curso'];

    $listaProfesores=buscarProfesoresAsociadosACurso($id_curso);

    $filtroProfesores="";

    if(count($listaProfesores)==1){
        $filtroProfesores="AND teoria_cursos.id_usuario='".$listaProfesores[0]['id_usuario']."'";
    }


    if(count($listaProfesores)>1){

        for($i=0;$i<count($listaProfesores);$i++){
            $filtroProfesores=$filtroProfesores."teoria_cursos.id_usuario=".$listaProfesores[$i]['id_usuario']."";

            if($i<(count($listaProfesores)-1)){
                $filtroProfesores=$filtroProfesores." OR ";
            }
            
        }

        //var_dump($filtroProfesores);

        $test="SELECT titulo FROM teoria_cursos
                    WHERE id_ejercicio=$id_ejercicio
                    AND ($filtroProfesores)
                    UNION
                    SELECT titulo FROM teoria_sistema
                    WHERE id_ejercicio=$id_ejercicio";

        //var_dump($test);

    }

    $accesoDatos = Acceso_a_datos::obtenerConexionBD();
    $consulta = $accesoDatos->prepararConsulta("SELECT teoria_cursos.titulo,teoria_cursos.tipo FROM teoria_cursos
                                                WHERE teoria_cursos.id_ejercicio=$id_ejercicio
                                                AND ($filtroProfesores)
                                                UNION
                                                SELECT teoria_sistema.titulo,teoria_sistema.tipo FROM teoria_sistema
                                                WHERE teoria_sistema.id_ejercicio=$id_ejercicio");
    
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function buscarContenidosTeoricosAvisualizar($id_usuario,$titulo,$tipo){

    if($tipo=="Profesor"){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT titulo,contenido 
                                                    FROM teoria_cursos
                                                    WHERE teoria_cursos.id_usuario=$id_usuario
                                                    AND titulo='$titulo'");
        $consulta->execute();

        if($consulta->rowCount()>0){
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    if($tipo=="Alumno"){
        //LAS SIGUIENTES FUNCIONES SE ENCUENTRAN EN UsuariosPorCurso.php
        $curso=buscarCursoAlumno($id_usuario);
            
        $id_curso=$curso[0]['id_curso'];

        $listaProfesores=buscarProfesoresAsociadosACurso($id_curso);
        
        $filtroProfesores="";

        for($i=0;$i<count($listaProfesores);$i++){
            $filtroProfesores=$filtroProfesores."teoria_cursos.id_usuario=".$listaProfesores[$i]['id_usuario']."";

            if($i<(count($listaProfesores)-1)){
                $filtroProfesores=$filtroProfesores." OR ";
            }  
        }

        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta(" SELECT teoria_cursos.titulo,
                                                            teoria_cursos.contenido,
                                                            usuarios.nombre,
                                                            usuarios.apellido 
                                                    FROM teoria_cursos,usuarios
                                                    WHERE ($filtroProfesores)
                                                    AND teoria_cursos.titulo='$titulo'
                                                    AND teoria_cursos.id_usuario=usuarios.id_usuario;");
        $consulta->execute();

        if($consulta->rowCount()>0){
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

    }

    //SI NO ENCUENTRA LA TEORIA EN TEORIA CURSOS LA BUSCA DEL SISTEMA

    $consulta=null;

    $consulta = $accesoDatos->prepararConsulta("SELECT titulo,contenido,
                                                FROM teoria_sistema
                                                WHERE titulo=$titulo");
                                                
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
    
}

function buscarNormativaRelacionada($id_ejercicio){

    $accesoDatos = Acceso_a_datos::obtenerConexionBD();
    $consulta = $accesoDatos->prepararConsulta("SELECT nombre,url from normativa_relacionada_general
                                                UNION
                                                SELECT nombre,url FROM normativa_relacionada_especifica
                                                WHERE id_ejercicio=$id_ejercicio");
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}



















//CREAR TEORIA SISTEMA
    function crearTeoriaSistema($teoria){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $id_teoria=$teoria["id_teoria"];
        $id_ejercicio=$teoria["id_ejercicio"];
        $titulo=$teoria["titulo"]; 
        $contenido=$teoria["contenido"];
        $tipo=$teoria["tipo"];

        $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_sistema
                                                    VALUES
                                                    ($id_teoria,$id_ejercicio,'$titulo','$contenido','$tipo')");
        $consulta->execute();
        return $consulta;
    }



//CREAR TEORIA CURSO

/*
    function obtenerListaTeoriasNav($teoria){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $id_teoria=$teoria["id_teoria"];
        $id_ejercicio=$teoria["id_ejercicio"];
        $id_usuario=$teoria["id_usuario"];
        $titulo=$teoria["titulo"]; 
        $contenido=$teoria["contenido"];
        $tipo=$teoria["tipo"];
        $lista_cursos=json_encode($teoria["lista_cursos"]);

        $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_cursos
                                                    VALUES
                                                    ($id_teoria,$id_usuario,$id_ejercicio,'$titulo','$contenido','$tipo','$lista_cursos')");
        $consulta->execute();
        return $consulta;
    }
*/

/*
    function buscarListaConceptosApoyo($id_usuario,$tipo_usuario){

        $accesoDatos = Acceso_a_datos::obtenerConexionBD();


        if($tipo_usuario=="Profesor"){
            $consulta = $accesoDatos->prepararConsulta("SELECT titulo FROM teoria_sistema
                                                        UNION
                                                        SELECT titulo FROM teoria_cursos
                                                        WHERE teoria_cursos.id_usuario=$id_usuario
                                                        ORDER BY titulo ASC");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        else if($tipo_usuario=="Alumno"){
            $consulta = $accesoDatos->prepararConsulta("SELECT titulo FROM teoria_sistema
                                                        UNION
                                                        SELECT titulo FROM teoria_cursos
                                                        WHERE teoria_cursos.id_usuario=$id_usuario
                                                        ORDER BY titulo ASC");

        }

        $consulta = $accesoDatos->prepararConsulta("SELECT titulo FROM teoria_sistema
                                                    UNION
                                                    SELECT titulo FROM teoria_cursos
                                                    WHERE teoria_cursos.id_usuario=$id_usuario
                                                    ORDER BY titulo ASC");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

//BUSCAR TODOS LOS CONCEPTOS DISPONIBLES PARA UN TIPO DE EJERCICIO PARTICULAR.
    function buscarTitulosSengunEjercicio($id_ejercicio){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT titulo FROM teoria_sistema
                                                    WHERE teoria_sistema.id_ejercicio=$id_ejercicio
                                                    UNION
                                                    SELECT titulo FROM teoria_cursos
                                                    WHERE teoria_cursos.id_ejercicio=$id_ejercicio
                                                    AND
                                                    ORDER BY titulo ASC'");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
        
//BUSCAR CONTENIDO TEORIA SISTEMA.
    function buscarContenidoTeoriaSistema($titulo){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT contenido
                                                    FROM teoria_sistema
                                                    where teoria_sistema.titulo='$titulo'");
        $consulta->execute();
          return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
*/




    

?>


