<?php

    class Opciones{

        public $id_opcion;
        public $nombre_opcion;
        public $descripcion_opcion;
        public $tipo_opcion;
        public $url_imagen_opcion;
      
        public static function buscar_opciones_profesor(){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM opciones_profesor");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Opciones');
        }

    }

?>