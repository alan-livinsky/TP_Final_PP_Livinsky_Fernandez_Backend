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
require __DIR__ . '/controllers/EjerciciosController.php';
require __DIR__ . '/controllers/OpcionesController.php';
require __DIR__ . '/controllers/MaterialesController.php';

require __DIR__ . '/entidades/Usuarios.php';
require __DIR__ . '/entidades/Ejercicios.php';
require __DIR__ . '/entidades/Cursos.php';
require __DIR__ . '/entidades/MenuPrincipal.php';
require __DIR__ . '/entidades/Opciones.php';
require __DIR__ . '/entidades/Materiales.php';
require __DIR__ . '/entidades/SolicitudRecuperacion.php';

require __DIR__ . '/librerias/RecuperacionContraseña.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

//Instanciar App
$app = AppFactory::create();

//Middleware <<Error - Por defecto de Slim>>
$errorMiddleware=$app->addErrorMiddleware(true,true,true);
//TEST PARA MANEJAR MEJOR LOS ERRORES DE SLIM
//$errorHandler=$errorMiddleware->getDefaultErrorHandler();
//$errorHandler->forceContentType('application/json');

//Middleware <<Validacion JWT - tuupola/slim-jwt-auth>>
//Automaticamente decodifica el token y lo guarda en $request->get_getAttribute("token");
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secure" => false,//Evitar error https
    "secret" => $_ENV['JWT_SECRET'],
    "algorithm" => ["HS256"],
    "path" => ["/"], 
    "ignore" => ["/Bienvenido","/Usuarios/registro","/Usuarios/loguin",
                "/Usuarios/recuperarContrase","/cargaDeFuego/listaMateriales",
                "/cargaDeFuego/datosMaterial","/Usuarios/emailRecuperacion/"],
    
    "error" => function ($response, $arguments){
        $data["status"]="error";
        $data["message"]=$arguments["message"];
     
        return $response
            /*Por Defecto el middleware retorna 401 pero por algun motivo en el front
              no comprende el 401 como tal si no lo aclaro con withStatus en la api*/
            ->withStatus(401)
            ->withHeader("Content-Type","application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
]));

//Middleware <<CORS - Por defecto de Slim>>
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {  
    $response = $handler->handle($request);
    $requestHeaders=$request->getHeaderLine('Access-Control-Request-Headers');
    $response=$response->withHeader('Access-Control-Allow-Origin', '*');
    $response=$response->withHeader('Access-Control-Allow-Methods', 'get,post,PUT,DELETE,options');
    $response=$response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
    return $response;
});

//<<Rutas>>
$app->get('/',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Token Test");
    return $response;
});

$app->get('/Bienvenido',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Bienvenido a SAE-SH");
    return $response;
});

$app->group('/Usuarios', function (RouteCollectorProxy $group){
    $group->get('[/]',\UsuariosController::class.':retornarListaUsuarios');
    $group->get('/lista',\UsuariosController::class.':retornarListaUsuarios');
    
    //AREGLAR GIONES BAJOS MINUSCULA MAYUSCULA
    $group->post('/registro',\UsuariosController::class.':retornarEstadoRegistro');
    $group->post('/loguin',\UsuariosController::class.':retornarTokenAcceso');
    $group->delete('/borrar_cuenta',\UsuariosController::class.':retornarEstadoEliminacionC');
    $group->put('/actualizar_contraseña',\UsuariosController::class.':retornarEstadoActualizacionContraseña');
    $group->post('/recuperarContrase',\UsuariosController::class.':retornarEmailDeRecuperacion');

    $group->get('/emailRecuperacion/{selector}/{token}','test');
 
});

$app->group('/Acceder_pagina', function (RouteCollectorProxy $group){
    $group->get('/menu_principal',\MenuPrincipalController::class.':retornarAccesoMenuPrincipal');
    $group->post('/menu_principal/validarToken',\MenuPrincipalController::class.':mantenerAccesoMenuPrincipal');
});

$app->group('/Menu_principal', function (RouteCollectorProxy $group){
    $group->get('/lista_ejercicios/cargar',\EjerciciosController::class.':retornarListaEjerciciosMenu');
    $group->get('/lista_opciones_profesor/cargar',\OpcionesController::class.':retornarOpciones_profesor');
    $group->get('/lista_opciones_alumno/cargar',\EjerciciosController::class.':retornarOpcionesMenuPrincipal');
});

$app->group('/cargaDeFuego', function (RouteCollectorProxy $group){
    $group->get('/listaMateriales',\MaterialesController::class.':retornarListaMateriales');
    $group->get('/datosMaterial/{material}',\MaterialesController::class.':retornarDatosMaterial');
});




$app->run();

?>