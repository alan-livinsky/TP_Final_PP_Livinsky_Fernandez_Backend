<?php

error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;



require __DIR__ .'/../vendor/autoload.php';
require __DIR__ .'/acceso_a_datos/Acceso_a_datos.php';
require __DIR__ . '/controllers/UsuariosController.php';
require __DIR__ . '/entidades/Usuarios.php';
require __DIR__ . '/entidades/Cursos.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

// Instantiate App
$app = AppFactory::create();
// Add error middleware
$app->addErrorMiddleware(true, true, true);

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    
    $response = $handler->handle($request);
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', 'get,post');
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    return $response;
});



$app->group('/Usuarios', function (RouteCollectorProxy $group) {

    $group->get('/loguin',function(Request $request, Response $response, array $args) { 
        $response->getBody()->write("Hello");
        return $response;
    });
   //La ñ no funciona
    $group->get('/loguin/{usuario}/{contrasea}',\UsuariosController::class.':retornarUsuario');

    /*$group->post('/registrar'.\UsuariosController::class.':retornarEstadoRegistro');*/
    $response=$request->getBody();
    return $response;
    $group->post('/registrar',function(Request $request,Response $response,array $args){
        $response= $app->$request->getBody();
        return $response;
    });

    
});


$app->get('[/]',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Hello");
    return $response;
});



$app->run();