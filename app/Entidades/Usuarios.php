<?php

    function buscar_lista_usuarios(){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta('SELECT * FROM usuarios');
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function buscar_usuario($email,$password){

        var_dump($email);
   
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $buscarHash=$accesoDatos->prepararConsulta("SELECT password FROM usuarios WHERE email='$email'");
        $buscarHash->execute();
        $hash=$buscarHash->fetchAll(PDO::FETCH_ASSOC);

        var_dump($hash);

        if (password_verify($password,$hash[0]['password'])){
            $password=$hash[0]['password'];
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE email='$email' AND password='$password'");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            return;
        }
    }

    function buscarUsuarioPorID($id_usuario,$password){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 

        echo "SELECT password FROM usuarios WHERE id_usuario=$id_usuario";

        $buscarHash=$accesoDatos->prepararConsulta("SELECT password FROM usuarios WHERE id_usuario=$id_usuario");
        $buscarHash->execute();
        $hash=$buscarHash->fetchAll(PDO::FETCH_ASSOC);



        if (password_verify($password,$hash[0]['password'])){
            $password=$hash[0]['password'];

            echo "SELECT * FROM usuarios WHERE id_usuario='$id_usuario' AND password='$password'";

            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE id_usuario='$id_usuario' AND password='$password'");
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
            $password=password_hash($datosUsuario['password'],PASSWORD_DEFAULT);
            $nombre=$datosUsuario['nombre'];
            $apellido=$datosUsuario['apellido'];
           
    
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios 
                                                      values 
                                                      ($id_usuario,'$email','$password','$nombre','$apellido','$tipo_usuario')");
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
    
    function eliminar_usuario($id_usuario){
        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        
        $consulta=$accesoDatos->prepararConsulta("DELETE FROM usuarios 
                                                      WHERE id_usuario=$id_usuario");
        $consulta->execute();

        $estado="Cuenta Eliminada";
        return $estado;
    }

    function actualizar_password($id_usuario,$password){
        $password=password_hash($password,PASSWORD_DEFAULT);

        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        echo "UPDATE usuarios 
        SET password='$password'
        WHERE id_usuario='$id_usuario'";
        $consulta=$accesoDatos->prepararConsulta("UPDATE usuarios 
                                                  SET password='$password'
                                                  WHERE id_usuario=$id_usuario");
        $consulta->execute();

        //VER MEJOR FORMA DE VALIDAR EL RESULTADO DE ESTE TIPO DE CONSULTA
        $estado="Actualizacion completada";

        return $estado;  
    }

?>