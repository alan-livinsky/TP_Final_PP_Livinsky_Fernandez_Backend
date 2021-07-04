<?php

class Acceso_a_datos{
    private static $obj_BD;
    private $objetoPDO;
    
    private function __construct(){
    
        $dsn = "pgsql:host=".$_ENV['DB_HOST'] 
               .";port=".$_ENV['DB_PORT']
               .";dbname=".$_ENV['DB_NAME']
               .";user=".$_ENV['DB_USERNAME']
               .";password=".$_ENV['DB_PASSWORD']
               .";";

        try {
            $this->objetoPDO = new PDO($dsn,$_ENV['DB_USERNAME'],$_ENV['DB_PASSWORD'], 
                   array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }
    
    public static function obtenerConexionBD(){
        if(!isset(self::$obj_BD)){
            self::$obj_BD=new Acceso_a_datos();
        }
        return self::$obj_BD;
    }

    public function prepararConsulta($sql){
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimaIdInsertada($secuencia_autoincremento_postgress){
        return $this->objetoPDO->lastInsertId($secuencia_autoincremento_postgress);
    }

}