<?php

    class Materiales{
        public $id_material;
        public $nombre;
        public $riesgo;
        public $poder_calorifico;
        public $fuente_de_informacion;
        
        public function buscar_lista_materiales(){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD();
            $consulta=$accesoDatos->prepararConsulta("SELECT nombre FROM materiales");
            $consulta->execute();
            //return $consulta->fetchAll(PDO::FETCH_CLASS,'Materiales');
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public function buscarDatosMaterial($nombre){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD();
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM materiales WHERE nombre='$nombre'");
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Materiales');
        }

    }
?>