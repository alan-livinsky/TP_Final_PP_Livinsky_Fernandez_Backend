<?php

    function buscarListaDeTitulos($id_ejercicio){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT titulo
                                                    FROM teoria_sistema
                                                    where teoria_sistema.id_ejercicio='$id_ejercicio'");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function buscarTeorias(){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT * FROM teoria_sistema" );                                         
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

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

?>


