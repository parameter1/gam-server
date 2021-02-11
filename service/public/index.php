<?php
require '../vendor/autoload.php';

use Parameter1\Controller\DefaultController;
use Parameter1\Error\JsonErrorRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpException;

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Add custom error render and force JSON for all responses
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer('application/json', JsonErrorRenderer::class);
$errorHandler->forceContentType('application/json');

$app->get('/', DefaultController::class . ':index')->setName('index');
$app->get('/favicon.ico', DefaultController::class . ':favicon');
$app->run();
