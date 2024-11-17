<?php

namespace App\Middleware;

use App\Config\Settings;
use App\Handlers\ErrorLogger;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Exception\HttpUnauthorizedException;
use Throwable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class AuthenticationMiddleware implements MiddlewareInterface {
    public function process(Request $request, Handler $handler): Response {
        $auth = $request->getHeader('Authorization');
        if(!empty($auth)) {
            $_token = $auth[0];
            $token = substr($_token, strpos($_token, ' '));
            $jwt = trim($token);

            $secKey = Settings::get('app', 'secret_key');
            try {
                    $decoded = JWT::decode($jwt, new Key($secKey, 'HS256'));
                    $userId = $decoded->id;
                    $iat = $decoded->iat;

                    $request = $request->withAttribute('userId', $userId);
                    $request = $request->withAttribute('iat', $iat);
                    $response = $handler->handle($request);
                    return $response;
                } catch (Throwable $e) {
                    ErrorLogger::logError('AuthErrors', 'JWT Authentication: ' . $e->getMessage(), [
                        'UserId' => $userId,
                        'JWT' => $jwt,
                        'File' => $e->getFile(),
                        'Line' => $e->getLine(),
                        'Code' => $e->getCode(),
                        'Trace' => $e->getTrace()
                    ]);
                }  
        }
        throw new HttpUnauthorizedException($request, 'Invalid authentication credentials.');
    }
}