<?php

    class Materiales{
        public $id_material;
        public $riesgo;
        public $poder_calorifico;
        public $fuente_informacion;
        
        public function buscar_lista_materiales(){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD();
            $consulta=$accesoDatos->prepararConsulta("SELECT id_materiasl FROM materiales");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Materiales');
        }

        public function buscarDatosMaterial($id_material){
            
            $accesoDatos=Acceso_a_datos::obtenerConexionBD();

            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM materiales WHERE id_material='$id_material'");
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Materiales');
        }

    }

?>