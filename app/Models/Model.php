<?php

namespace App\Models;

use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

/**
 * Provide features that us universal to all Models
 *
 */ 
abstract class Model {

    /**
     * Provide logging function to all Models
     *
     * Can be accessed by all models that extend the Model Class
     */ 
    protected function logError(PDOException $e) {
        $message = $e->getMessage();
        $context = [
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
            'Code' => $e->getCode(),
            'Trace' => $e->getTrace()
        ];
        $logger = new Logger('database');
        $streamHandler = new StreamHandler(__DIR__ . '/../../logs/Errors.log', Level::Debug);
        $logger->pushHandler($streamHandler);
        $logger->error($message, $context);
    }
}