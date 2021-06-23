<?php

error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/acceso_a_datos/Acceso_a_datos.php';
require __DIR__ . '/controllers/UsuariosController.php';
require __DIR__ . '/entidades/Usuarios.php';
require __DIR__ . '/entidades/Cursos.php';

use Firebase\JWT\JWT;

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

//CORS middleware
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {  
    $response = $handler->handle($request);
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', 'get,post');
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
    return $response;
});

//JWT verificacion Middleware
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secure" => false,//Evitar error https
    "secret" => $_ENV['JWT_SECRET'],
    "error" => function ($response, $arguments){
        $data["status"]="error";
        $data["message"]=$arguments["message"];

        $response = $app->getResponseFactory()->createResponse();
        // echo "User NOT authorized.";
        return $response->withRedirect('https://tp-final-pp-liv-ferz-frontend.herokuapp.com/',301);
        //return $response->withHeader('Location','/')->withStatus(302);
        
        /*return $response
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));*/
    }
]));



$app->get('[/]',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Bienvenido a SAE-SH");
    return $response;
});

$app->group('/Usuarios', function (RouteCollectorProxy $group) {
   //La ñ no funciona
    $group->get('/loguin/{usuario}/{contrasea}',\UsuariosController::class.':retornarUsuario');
    $group->post('/registro',\UsuariosController::class.':retornarEstadoRegistro');
});

$app->group('/Token', function (RouteCollectorProxy $group) {
    $group->get('/loguin',function(Request $request, Response $response, array $args) { 
        
        $privateKey = $_ENV['JWT_SECRET'];
        
        $payload = array(
            "nom" => "Alan",
            "ape" => "Livinsky"
        );
       
        JWT::$leeway = 240; 

        $jwt = JWT::encode($payload,$privateKey,'HS256');
        //El header se autogenera con el algoritmo y tipo de token
        //Tambien se encripta automaticamente en base64url   

        $response=$jwt;
        return $response;
    });
});




$app->run();