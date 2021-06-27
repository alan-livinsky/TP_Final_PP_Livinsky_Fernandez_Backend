<?php

error_reporting(-1);
ini_set('display_errors',1);

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

//Instantiate App
$app = AppFactory::create();

//Middleware <<Error - Por defecto de Slim>>
$errorMiddleware=$app->addErrorMiddleware(true,true,true);
$errorHandler=$errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

//Middleware <<Validacion JWT - tuupola/slim-jwt-auth>>
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secure" => false,//Evitar error https
    "secret" => $_ENV['JWT_SECRET'],
    "algorithm" => ["HS256"],
    "path" => "/", 
    "ignore" => ["/Bienvenido","/Usuarios/registro","/Usuarios/loguin"],
    
    "error" => function ($response, $arguments){
        $data["status"]="error";
        $data["message"]=$arguments["message"];
     
        return $response
            /*Por Defecto el middleware retorna 401 pero por algun motivo en el front
              no comprende el 401 como tal si no lo aclaro con withStatus en la api*/
            ->withStatus(401)
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
]));

//Middleware <<CORS - Por defecto de Slim>>
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {  
    $response = $handler->handle($request);
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', 'get,post,put,delete,options');
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
    return $response;
});


//<<Rutas>>
$app->get('/Bienvenido',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Bienvenido a SAE-SH");
    return $response;
});

$app->group('/Usuarios', function (RouteCollectorProxy $group) {
    $group->post('/registro',\UsuariosController::class.':retornarEstadoRegistro');
    $group->get('/ver_usuario/{usuario}/{contrasea}',\UsuariosController::class.':retornarUsuario');
    $group->get('/loguin/{usuario}/{contrasea}',\UsuariosController::class.':retornarTokenAcceso');
    //pasar a post con json 
    $group->get('/lista',\UsuariosController::class.':retornarListaUsuarios');
});

$app->group('/Acceder_pagina', function (RouteCollectorProxy $group) {
    $group->get('/menu_principal',\MenuPrincipalController::class.':retornarAccesoMenuPrincipal');
    $group->get('/menu_principal/validarToken',\MenuPrincipalController::class.':mantenerAccesoMenuPrincipal');
});

$app->group('/Ejercicios', function (RouteCollectorProxy $group) {
    $group->get('/opciones_menu_principal/cargar',\MenuPrincipalController::class.':mantenerAccesoMenuPrincipal');
    
});



$app->run();