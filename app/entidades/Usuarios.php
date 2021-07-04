<?php

    class Usuarios{

        public $id_usuario;
        public $email;
        public $contraseña;
        public $nombre;
        public $apellido;
        public $tipo_usuario;

        public static function buscar_usuario($email,$contraseña){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE email='$email' AND contraseña='$contraseña'");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Usuarios');
        }

        public static function buscar_lista_usuarios(){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta('SELECT * FROM usuarios');
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Usuarios');
        }

        public function registrar_usuario($id_usuario,$email,$contraseña,$nombre,$apellido,$tipo_usuario){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios 
                                                    values 
                                                    ($id_usuario,'$email','$contraseña','$nombre','$apellido','$tipo_usuario')");
            $consulta->execute();


            $id_usuario_registrado=$accesoDatos->obtenerUltimaIdInsertada('usuarios_id_usuario_seq');


            if($data['tipo_usuario']=="Alumno" && $id_usuario_registrado1!=null){
                $cursos=new Cursos();
                asociarUsuarioCurso($data['año'],$data['comision'],$data['turno'],$id_usuario_registrado);
            }
    

            $estado="Registro completado";
            return $estado;     
        }

        public function eliminar_usuario($email){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("DELETE FROM usuarios 
                                                    WHERE email='$email'");
            $consulta->execute();
            $estado="Cuenta Eliminada";
            return $estado;
        }

        public function actualizar_contraseña($email,$contraseña){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("UPDATE usuarios 
                                                        SET contraseña='$contraseña'
                                                        WHERE email='$email'");
            $consulta->execute();
            $estado="Actualizacion completada";
            return $estado;  
        }

    }
?>