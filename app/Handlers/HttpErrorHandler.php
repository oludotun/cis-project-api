<?php

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler;

class HttpErrorHandler extends ErrorHandler {

    protected function respond(): ResponseInterface 
    {
        $exception = $this->exception;
        // Log the error using ErrorLogger
        $context = [
            'File' => $exception->getFile(),
            'Line' => $exception->getLine(),
            'Code' => $exception->getCode(),
            'Trace' => $exception->getTrace()
        ];
        ErrorLogger::logError('httpErrors', $exception->getMessage(), $context);

        // if ($exception instanceof HttpException) {
        //     $statusCode = $exception->getCode();
        //     if($statusCode == 401) {
        //         $response = $this->responseFactory->createResponse($statusCode);
        //         $response = $response->withHeader('Content-type', 'application/json');
        //         $payload = json_encode(['error' => [
        //             'name' => 'AuthenticationError',
        //             'message' => 'Invalid authentication credentials.'
        //         ]]);
        //         $response->getBody()->write($payload);
        //         return $response;
        //     }
        // }

        // if ($exception instanceof HttpException) {
        //     $statusCode = $exception->getCode();
        //     if($statusCode == 404) {
        //         $response = $this->responseFactory->createResponse($statusCode);
        //         $response = $response->withHeader('Content-type', 'application/json');
        //         $payload = json_encode(['error' => [
        //             'name' => 'NotFoundError',
        //             'message' => 'The requested URL does not exist.'
        //         ]]);
        //         $response->getBody()->write($payload);
        //         return $response;
        //     }
        // }

        $response = $this->responseFactory->createResponse($exception->getCode());
        $response = $response->withHeader('Content-type', 'application/json');
        $payload = json_encode(['error' => [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ]]);
        $response->getBody()->write($payload);
        return $response;
        // return parent::respond();
    }
}