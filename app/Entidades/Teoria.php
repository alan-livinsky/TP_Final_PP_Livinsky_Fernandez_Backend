<?php

    //DENTRO DE ESTA ENTIDAD SE MANEJARA TANTO TEORIA DEL SISTEMA COMO DE LOS CURSOS

    //BUSCAR TODOS LOS CONCEPTOS EXISTENTES(PARA EDITAR TEORIA);
    function buscarListaDeTitulos($id_ejercicio){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT 
                                                    DISTINCT titulo
                                                    FROM teoria_sistema,teoria_cursos
                                                    ORDER BY titulo ASC");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    //BUSCAR TODOS LOS CONCEPTOS DISPONIBLES PARA UN TIPO DE EJERCICIO PARTICULAR.
    function buscarTitulosSengunEjercicio($id_ejercicio){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT 
                                                    DISTINCT titulo
                                                    FROM teoria_sistema,teoria_cursos
                                                    WHERE teoria_sistema.id_ejercicio='$id_ejercicio
                                                    ORDER BY titulo ASC'");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function crearTeoriaSistema($teoria){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $id_teoria=$teoria["id_teoria"];
        $id_ejercicio=$teoria["id_ejercicio"];
        $titulo=$teoria["titulo"]; 
        $contenido=$teoria["contenido"];
        $tipo=$teoria["tipo"];

        $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_sistema
                                                    VALUES
                                                    ($id_teoria,$id_ejercicio,'$titulo', '$contenido','$tipo')");
        $consulta->execute();
        return $consulta;
    }

    function crearTeoriaCurso($teoria){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        var_dump($teoria);

        $id_teoria=$teoria["id_teoria"];
        $id_ejercicio=$teoria["id_ejercicio"];
        $id_usuario=$teoria["id_usuario"];
        $titulo=$teoria["titulo"]; 
        $contenido=$teoria["contenido"];
        $tipo=$teoria["tipo"];
        $lista_cursos=json_encode($teoria["lista_cursos"]);

        var_dump($id_usuario);
      
        $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_cursos
                                                    VALUES
                                                    ($id_teoria,$id_usuario,$id_ejercicio,'$titulo','$contenido','$tipo','$lista_cursos')");
        $consulta->execute();
        return $consulta;
    }


    /*
    function buscarContenidoTeoriaSistema($titulo){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT contenido
                                                    FROM teoria_sistema
                                                    where teoria_sistema.titulo='$titulo'");
        $consulta->execute();
          return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function crearTeoriaSistema($teoria){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $id_teoria=$teoria["id_teoria"];
        $id_ejercicio=$teoria["id_ejercicio"];
        $titulo=$teoria["titulo"]; 
        $contenido=$teoria["contenido"];
        $tipo=$teoria["tipo"];

        $consulta = $accesoDatos->prepararConsulta("INSERT INTO teoria_sistema
                                                    VALUES
                                                    ($id_teoria,$id_ejercicio,'$titulo', '$contenido','$tipo')");
        $consulta->execute();
        return $consulta;
    }

    function actualizarContenidoTeoriaSistema($titulo, $contenido){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $consulta = $accesoDatos->prepararConsulta("UPDATE teoria_sistema
                                                    SET contenido = '$contenido' WHERE titulo = '$titulo'");
        $consulta->execute();
        return $consulta;
    }

    function borrarTeoria($titulo){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $consulta = $accesoDatos->prepararConsulta("DELETE from  teoria_sistema 
                                                    WHERE titulo = '$titulo'");
        $consulta->execute();
        return $consulta;
    }
    

    //POSIBLEMENTE NO SE USEN ESTAS FUNCIONES
    function actualizarTipoTeoria($tipo,$tipo_nuevo){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();

        $consulta = $accesoDatos->prepararConsulta("UPDATE teoria_sistema
                                                    SET tipo = '$tipo_nuevo' WHERE tipo = '$tipo'");
        $consulta->execute();
        return $consulta;
    }
    */

?>


