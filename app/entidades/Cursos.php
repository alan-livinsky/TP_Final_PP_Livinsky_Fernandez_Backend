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

        public function asociarUsuarioCurso($año,$comision,$turno,$id_usuario){
            $cursoEncontrado=$this->buscarCurso($año,$comision,$turno);
             if(count($cursoEncontrado)!=0){
                $accesoDatos=Acceso_a_datos::obtenerConexionBD();
                $consulta=$accesoDatos->prepararConsulta("INSERT INTO usuarios_curso 
                                                            VALUES ($id_usuario,$cursoEncontrado)");
                $consulta->execute();
             }

        }

    }

?>