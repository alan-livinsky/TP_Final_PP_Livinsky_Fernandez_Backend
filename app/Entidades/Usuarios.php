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

    function registrar_usuario($data){
        $id_usuario=$data['id_usuario'];
        $email=$data['email'];
        $contraseña=password_hash($data['contraseña'],PASSWORD_DEFAULT);
        $nombre=$data['nombre'];
        $apellido=$data['apellido'];
        $tipo_usuario=$data['tipo_usuario'];

        $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
        $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios 
                                                  values 
                                                  ($id_usuario,'$email','$contraseña','$nombre','$apellido','$tipo_usuario')");
        $consulta->execute();

        //VERIFICAR REGISTRO EXITOSO
        $id_usuario_registrado=$accesoDatos->obtenerUltimaIdInsertada('usuarios_id_usuario_seq');

        if($data['tipo_usuario']=="Alumno" && $id_usuario_registrado!=null){
            $cursos=new Cursos();
            $cursos->asociarUsuarioCurso($data['año'],$data['comision'],$data['turno'],$id_usuario_registrado);  
        }
    
        $estado="Registro completado";
        return $estado;     
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