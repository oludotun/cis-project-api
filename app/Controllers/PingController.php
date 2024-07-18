<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PingController
{
    public function ping(Request $request, Response $response)
    {
        $payload = json_encode(['message' => "Hello World!"]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }
}