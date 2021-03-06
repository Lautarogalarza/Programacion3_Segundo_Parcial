<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class ValidarToken
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        
        $response = new Response();
        
        $headers = getallheaders();
        $token = $headers['token'] ?? '';
         $key = "pro3-parcial";   ;
        try {
            $payload = JWT::decode($token, $key, array('HS256'));
        } catch (\Throwable $th) {
            $payload= null;
          }
        
           
        if ($payload == null) {
            $response->getBody()->write('Token invalido');
        } else {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);
            
            $response->getBody()->write($existingContent);
        }
        
        return $response;
    }
}
