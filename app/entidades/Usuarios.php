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
            try{
                $consulta->execute();
                return "Registro completado";
            }
            catch(PDOExeption $e){
                echo 'Exception -> ';
                var_dump($e->getMessage());
                return "error";
            }       
        }

        public function borrar_cuenta($email){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("DELETE FROM usuario 
                                                    WHERE email='$email'");
           
            $consulta->execute();
            $estado="Cuenta Eliminada";
            return $estado;
        }



        //ESTA MAL POR MOTIVOS DE SEGURIDAD
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