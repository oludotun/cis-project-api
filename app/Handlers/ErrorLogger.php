<?php

namespace App\Handlers;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class ErrorLogger {
    public static function logError(string $loggerName, string $message, array $context = []) : void 
    {
        // Create the Monolog logger
        $logger = new Logger($loggerName);
        $logFile = __DIR__ . '/../../logs/Errors.log';
        $logger->pushHandler(new StreamHandler($logFile, Level::Debug));
        $logger->error($message, $context);
    }    
}