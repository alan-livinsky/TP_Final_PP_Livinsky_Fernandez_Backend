<?php

    class Ejercicios{

        public $id_ejercicio;
        public $nombre_ejercicio;
        public $descripcion_ejercicio;
        public $url_imagen_ejercicio;
       
        public static function buscar_opciones_menuPrincipal(){
            $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
            $consulta=$accesoDatos->prepararConsulta("SELECT * FROM ejercicios");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS,'Ejercicios');
        }

    }

?>