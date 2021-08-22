<?php

    function buscar_lista_usuarios(){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta('SELECT * FROM usuarios');
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function buscar_usuario($email,$contraseña){

        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $buscarHash=$accesoDatos->prepararConsulta("SELECT contraseña FROM usuarios WHERE email='$email'");
        $buscarHash->execute();
        $hash=$buscarHash->fetchAll(PDO::FETCH_ASSOC);

        if (password_verify($contraseña,$hash[0]['contraseña'])){
            $contraseña=$hash[0]['contraseña'];
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE email='$email' AND contraseña='$contraseña'");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            return;
        }
    }

    function registrarUsuario($datosUsuario){

        $tipo_usuario=$datosUsuario['tipo_usuario'];

        $verificarCursoExistente=buscarCurso($datosUsuario['año'],$datosUsuario['comision'],$datosUsuario['turno']);

        if(($verificarCursoExistente && $tipo_usuario=="Alumno") || $tipo_usuario=="Profesor"){
            $id_usuario=$datosUsuario['id_usuario'];
            $email=$datosUsuario['email'];
            $contraseña=password_hash($datosUsuario['contraseña'],PASSWORD_DEFAULT);
            $nombre=$datosUsuario['nombre'];
            $apellido=$datosUsuario['apellido'];
           
    
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios 
                                                      values 
                                                      ($id_usuario,'$email','$contraseña','$nombre','$apellido','$tipo_usuario')");
            $consulta->execute();
    
            //VERIFICAR REGISTRO EXITOSO
            $idUltimoRegistro=$accesoDatos->obtenerUltimaIdInsertada('usuarios_id_usuario_seq');
    
            if($datosUsuario['tipo_usuario']=="Alumno" && $idUltimoRegistro!=null){
                $resultadoAsociacion=asociarAlumnoCurso($datosUsuario['año'],$datosUsuario['comision'],$datosUsuario['turno'],$idUltimoRegistro);  
            } 
        }
        else{
            return "Curso inexistente";
        } 
    }
    
    function eliminar_usuario($email){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        
        $consulta=$accesoDatos->prepararConsulta("DELETE FROM usuarios 
                                                      WHERE email='$email'");
        $consulta->execute();

        $estado="Cuenta Eliminada";
        return $estado;
    }

    function actualizar_contraseña($email,$contraseña){
        $contraseña=password_hash($contraseña,PASSWORD_DEFAULT);

        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta("UPDATE usuarios 
                                                  SET contraseña='$contraseña'
                                                  WHERE email='$email'");
        $consulta->execute();

        //VER MEJOR FORMA DE VALIDAR EL RESULTADO DE ESTE TIPO DE CONSULTA
        $estado="Actualizacion completada";

        return $estado;  
    }

?>