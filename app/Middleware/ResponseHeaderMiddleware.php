<?php

namespace App\Middleware;

use App\Config\Settings;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

final class ResponseHeaderMiddleware  implements MiddlewareInterface 
{

    public function process(Request $request, Handler $handler): Response 
    {
        $oldResponse = $handler->handle($request);
        $newResponse = $oldResponse
            ->withHeader('Content-type', 'application/json')
            ->withHeader('X-Powered-By', 'Proprietary');
        $corsSettings = Settings::get('cors');
        $allowedOrigins = $corsSettings['allowed_origin'];
        $origin = $request->getHeader('Origin');
        if(!empty($origin) && in_array($origin[0], $allowedOrigins)) {
            $newResponse = $this->addCorsHeaders($newResponse, $origin[0]);
        }

        return $newResponse;
    }

    private function addCorsHeaders(Response $response, string $allowedOrigin) 
    {
        $corsSettings = Settings::get('cors');
        $newResponse = $response
            ->withHeader('Access-Control-Allow-Origin', $allowedOrigin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, X-Session-Token, X-Authenticated-UserId, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Max-Age', $corsSettings['access_control_max_age'])
            ->withHeader('Vary', 'origin')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
        return $newResponse;
    }
}