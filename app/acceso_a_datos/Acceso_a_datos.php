<?php

class Acceso_a_datos{
    private static $obj_BD;
    private $objetoPDO;
    
    private function __construct(){
        $host=$_ENV['DB_HOST'];
        $user=$_ENV['DB_USERNAME'];
        $password=$_ENV['DB_PASSWORD'];
        $dbname=$_ENV['DB_NAME'];
        $port=$_ENV['DB_PORT'];

        $dsn = "pgsql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";

        try {
            $this->objetoPDO = new PDO($dsn, $user, $password, 
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



   

}