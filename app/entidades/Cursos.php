<?php

    class Cursos{
        public $id_curso;
        public $año;
        public $comision;
        public $turno;
        

        public function buscarCurso($año,$comision,$turno){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD();
            $consulta=$accesoDatos->prepararConsulta("SELECT id_curso FROM cursos Where año='$año' and comision='$comision' and turno='$turno'");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Cursos');
        }

        public function asociarUsuarioCurso(){

        }

    }

?>