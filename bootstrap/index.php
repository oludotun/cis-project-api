<?php

use App\Config\Settings;
use App\Handlers\HttpErrorHandler;
use Slim\Factory\AppFactory;
use App\Middleware\ResponseHeaderMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$appSettings = Settings::get('app');
$displayErrorDetails = $appSettings['app_env'] != 'production';
$logErrors = $appSettings['log_errors'];
$logErrorDetails = $appSettings['log_error_details'];

// Add error middleware with the custom error handler
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
$httpErrorHandler = new HttpErrorHandler($app->getCallableResolver(), $app->getResponseFactory());
$httpErrorHandler->forceContentType('application/json');
$errorMiddleware->setDefaultErrorHandler($httpErrorHandler);

// Add Headers to handle CORS
$app->add(new ResponseHeaderMiddleware());

// Load API Routes
$routes = require_once __DIR__ . '/../app/Routes/api.php';
$routes($app);

$app->run();