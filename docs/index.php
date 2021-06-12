<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";

if(isset($_SERVER["HTTP_ORIGIN"]))
{
    // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
    header("Access-Control-Allow-Origin:{$_SERVER['HTTP_ORIGIN']}");
}
else
{
    //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");    // cache for 10 minutes

if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
{
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}
//From here, handle the request as it is ok*/


require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true,true,true);

$app->get('/hola/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hola, $name");
    return $response;
});

$app->get('/materiales/{materiales}', function (Request $request, Response $response, array $args) {
    
    

        $Materiales=$_GET["materiales"];
        $Material="Material";
        $Riesgo="Riesgo";
        $Poder_Calorifico="Poder_Calorifico";
        $Unidad="Unidad";
        $borrar="borrar";
        $actualizar="actualizar";

    if($Materiales==="materiales"){

        $table="";
        $table.='<div class=col-md-12>';
        $table.='<table class="table table-striped table-bordered col-md-12">';
        $table.='<tr>';
        $table.='<th>Material</th>';
        $table.='<th>Riesgo</th>';
        $table.='<th>Poder Calorifico</th>';
        $table.='<th>Unidad</th>';
        $table.='<th>Editar Material</th>';
        $table.='<th>Borrar Material</th>';
        $table.='</tr>';

            $sql = 'SELECT * FROM materiales';
            $stmt = conectar()->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $resultado=$stmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($resultado as $fila){    
                $table.='<tr>';
                    $table.='<td id="'.$Material.$fila['material'].'">'.$fila['material'].'</td>';
                    $table.='<td id="'.$Riesgo.$fila['material'].'">'.$fila['riesgo'].'</td>';
                    $table.='<td id="'.$Poder_Calorifico.$fila['material'].'">'.$fila['poder_calorifico'].'</td>';
                    $table.='<td id="'.$Unidad.$fila['material'].'">'.$fila['unidad'].'</td>';
                    $table.='<td><input id="'.$fila['material'].'" type="button" value="Editar" class="btn btn-warning" onclick="editarMaterial(this.id)"></td>';
                    $table.='<td><input id="'.$borrar.$Material.$fila['material'].'" type="button" value="Borrar" onclick=borrarMaterial("'.$fila['material'].'") class="btn btn-danger"></td>';
                    $table.='<td style="display:none" id="'.$actualizar.$Material.$fila['material'].'"> <input type="button" value="Actualizar" class="btn btn-primary" onclick=actualizarMaterial("'.$fila['material'].'")></td>';
                $table.='</tr>';
        }   
        $table.='</table>';
        $table.='</div>';
        
        echo $table;

        //Desconexion
        $stmt=null;
        $pdo=null;
    }




});

$app->run();

/*php -S localhost:444 -t docs*/

?>