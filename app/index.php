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
require __DIR__ . '/controllers/MenuPrincipalController.php';
require __DIR__ . '/entidades/Usuarios.php';
require __DIR__ . '/entidades/Cursos.php';
require __DIR__ . '/entidades/MenuPrincipal.php';

use Firebase\JWT\JWT;

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();


// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true,true,true);


//Validacion JWT Middleware

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secure" => false,//Evitar error https
    "secret" => $_ENV['JWT_SECRET'],
    "path" => "/", 
    "ignore" => ["/Bienvenido","/Usuarios/registro","/Usuarios/loguin"],
    
    "error" => function ($response, $arguments){
        $data["status"]="error";
        $data["message"]=$arguments["message"];
     
        return $response
            ->withAddedHeader('Location','https://tp-final-pp-liv-ferz-frontend.herokuapp.com/')
            ->withStatus(302);
           
            //->withHeader("Content-Type", "application/json")
            //->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

//CORS Middleware
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {  
    $response = $handler->handle($request);
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', 'get,post,put,delete,options');
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
    return $response;
});

$app->get('/Bienvenido',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Bienvenido a SAE-SH");
    return $response;
});

$app->group('/Usuarios', function (RouteCollectorProxy $group) {
    $group->post('/registro',\UsuariosController::class.':retornarEstadoRegistro');
    $group->get('/loguin/{usuario}/{contrasea}',\UsuariosController::class.':retornarTokenAcceso');
    //pasar a post con json 
});


$app->group('/Acceder_pagina', function (RouteCollectorProxy $group) {
    $group->get('/menu_principal',\MenuPrincipalController::class.':retornarAccesoMenuPrincipal');

});






$app->run();