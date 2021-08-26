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

require __DIR__ . '/Acceso_a_datos/Acceso_a_datos.php';
require __DIR__ . '/Acceso_a_datos/ConsultasGenericas.php';

require __DIR__ . '/ManejoDeDatos/ManejoDeDatosUsuarios.php';
require __DIR__ . '/ManejoDeDatos/ManejoDeDatosMenuPrincipal.php';
require __DIR__ . '/ManejoDeDatos/ManejoDeDatosEjercicios.php';
require __DIR__ . '/ManejoDeDatos/ManejoDeDatosOpciones.php';
require __DIR__ . '/ManejoDeDatos/ManejoDeDatosMateriales.php';
require __DIR__ . '/ManejoDeDatos/ManejoDeDatosCursos.php';

require __DIR__ . '/Entidades/Usuarios.php';
require __DIR__ . '/Entidades/Ejercicios.php';
require __DIR__ . '/Entidades/Cursos.php';
require __DIR__ . '/Entidades/MenuPrincipal.php';
require __DIR__ . '/Entidades/Opciones.php';
require __DIR__ . '/Entidades/Materiales.php';
require __DIR__ . '/Entidades/UsuariosPorCurso.php';

require __DIR__ . '/Librerias/RecuperacionContraseña.php';
require __DIR__ . '/Librerias/LibreriaGeneral.php';

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
                "/cargaDeFuego/datosMaterial","/Usuarios/emailRecuperacion/",
                "/Usuarios/recuperarContrase/modificar","/Cursos/ListaFiltrada","/Cursos/Lista"],

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

    $group->get('/lista','retornarListaUsuarios');
    $group->post('/registro','retornarEstadoRegistro');
    $group->post('/loguin','retornarTokenAcceso');
    $group->delete('/borrar_cuenta','retornarEstadoEliminacionCuenta');
    $group->put('/actualizar_contraseña','retornarEstadoActualizacionContraseña');
    
    $group->post('/recuperarContrase','enviarEmailDeRecuperacion');
    $group->post('/recuperarContrase/modificar','retornarEstadoRecuperarContraseña');
    $group->get('/emailRecuperacion/{selector}/{token}','validarEnlaceRecContraseña');

});

$app->group('/Cursos', function (RouteCollectorProxy $group){
    $group->get('/Lista','retornarListaCursos');
    $group->get('/ListaFiltrada','retornarListaCursosFiltrada');
});


$app->group('/Acceder_pagina', function (RouteCollectorProxy $group){
    $group->get('/menu_principal','retornarAccesoMenuPrincipal');
    $group->post('/menu_principal/validarToken','mantenerAccesoMenuPrincipal');
});


$app->group('/Menu_principal', function (RouteCollectorProxy $group){
    $group->get('/lista_ejercicios/cargar','retornarListaEjerciciosMenu');
    $group->get('/lista_opciones_profesor/cargar','retornarOpciones_profesor');
    $group->get('/lista_opciones_alumno/cargar','retornarOpcionesMenuPrincipal');
});

$app->group('/cargaDeFuego', function (RouteCollectorProxy $group){
    $group->get('/listaMateriales','retornarListaMateriales');
    $group->get('/datosMaterial/{material}','retornarDatosMaterial');
});


$app->run();

?>