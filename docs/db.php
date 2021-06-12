<?php

require_once realpath("../vendor/autoload.php");
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();


function conectar(){

  try {
    //Setear datos de conexion a la base de datos
    /*
    $host = "ec2-3-214-136-47.compute-1.amazonaws.com";
    $user = "tpwpquxupjhbyx";
    $password = "7abae84eb2ace1c5d2b76d7b301e9754fc989197804caa5aaa71823a242a2349";
    $dbname = "dem0ki9n08ji1";
    $port = "5432";

    $dsn = "pgsql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";
    */

    $host=$_ENV['DB_HOST'];
    $user=$_ENV['DB_USERNAME'];
    $password=$_ENV['DB_PASSWORD'];
    $dbname=$_ENV['DB_NAME'];
    $port=$_ENV['DB_PORT'];

    $dsn = "pgsql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";

    //Crear una instancia de PDO
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
    
  }catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
}

?>

