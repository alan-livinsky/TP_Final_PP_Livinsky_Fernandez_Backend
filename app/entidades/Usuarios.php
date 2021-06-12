<?php

    class Usuarios{
        public $id_usuario;
        public $email;
        public $contraseña;

        public function obtenerUsuarios($tabla){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta('SELECT * FROM'.$tabla);
            $consulta->execute();
        }
    }

    class Alumnos extends Usuarios{
        public $id_alumno;
    }

    class Profesores extends Usuarios{
        public $id_profesor;
    }

    

?>