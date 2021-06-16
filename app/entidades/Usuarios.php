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
            $email="'".$email."'";
            $contraseña="'".$contraseña."'";
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE email=$email AND contraseña=$contraseña");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Usuarios');
        }


        
        public static function obtenerUsuarios(){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta('SELECT * FROM usuarios');
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Usuarios');
        }

        
        public function registrar_usuario($id_usuario,$email,$contraseña,$nombre,$apellido,$tipo_usuario){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios 
                                                      values (=$this->$email AND 
                                                    contraseña=$this->$contraseña");
            $consulta=execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Usuarios');
        }



    }

    /*
    class Alumnos extends Usuarios{
        public $id_alumno;
        public $id_curso;
    }

    class Profesores extends Usuarios{
        public $id_profesor;
    }
    */

?>