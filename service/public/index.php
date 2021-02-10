<?php
require '../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Parameter1\Controller\DefaultController;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addRoutingMiddleware();

// Define Custom Error Handler
$customErrorHandler = function (ServerRequestInterface $request, Throwable $exception) use ($app) {
  $payload = ['error' => $exception->getMessage()];
  $response = $app->getResponseFactory()->createResponse();
  $response->getBody()->write(
    json_encode($payload, JSON_UNESCAPED_UNICODE)
  );
  return $response->withStatus(500)->withHeader('content-type', 'application/json');;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->get('/', DefaultController::class . ':index');
$app->get('/favicon.ico', DefaultController::class . ':favicon');
$app->run();
