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

//JWT Middleware

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secret" => $_ENV['JWT_SECRET']
]));


$app->get('[/]',function(Request $request, Response $response, array $args) { 
    $response->getBody()->write("Bienvenido a SAE-SH");
    return $response;
});

$app->group('/Usuarios', function (RouteCollectorProxy $group) {
   //La ñ no funciona
    $group->get('/loguin/{usuario}/{contrasea}',
    \UsuariosController::class.':retornarUsuario')->add(new JWT_Middleware());
    $group->post('/registro',\UsuariosController::class.':retornarEstadoRegistro');
});

$app->group('/Token', function (RouteCollectorProxy $group) {
    $group->get('/loguin',function(Request $request, Response $response, array $args) { 
        
        /*
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'];
        
        $header = json_encode($header); 
        $header = base64_encode($header);

        $payload = [
            "nom" => "Alan",
            "ape" => "Livinsky",];

        $payload = json_encode($payload);		
        $payload = base64_encode($payload); 

        $signature = hash_hmac('sha256','$header.$payload',$_ENV['JWT_SECRET'],true);
        $signature = base64_encode($signature);
        $JWT=$header.$payload.$signature;

        echo $JWT;*/
        
        $privateKey = <<<EOD
        -----BEGIN RSA PRIVATE KEY-----
        MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
        vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
        5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
        AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
        bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
        Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
        cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
        5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
        ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
        k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
        qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
        eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
        B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
        -----END RSA PRIVATE KEY-----
        EOD;

        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC8kGa1pSjbSYZVebtTRBLxBz5H
        4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t
        0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4
        ehde/zUxo6UvS7UrBQIDAQAB
        -----END PUBLIC KEY-----
        EOD;

        $payload = array(
            "nom" => "Alan",
            "ape" => "Livinsky"
        );
       
        JWT::$leeway = 240; 

        $jwt = JWT::encode($payload,$privateKey,'RS256');
        //El header se autogenera con el algoritmo y tipo de token
        //Tambien se encripta automaticamente en base64url   

        echo "Encode:\n" . print_r($jwt, true) . "\n";

        $decoded = JWT::decode($jwt, $publicKey, array('RS256'));
        /*NOTE: This will now be an object instead of an associative array. To getan associative 
        array, you will need to cast it as such:*/
        $decoded_array = (array) $decoded;
        echo "Decode:\n" . print_r($decoded_array, true) . "\n";

        $response=$jwt;
        return $response;
    });
});




$app->run();