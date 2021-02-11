<?php
require '../vendor/autoload.php';

use Parameter1\Controller\DefaultController;
use Parameter1\Error\JsonErrorRenderer;
use Parameter1\Middleware\AdManagerServiceMiddleware;
use Parameter1\Util\SlimUtil;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

$app = AppFactory::create();

// Enable body parsing and routing
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Add custom error render and force JSON for all responses
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer('application/json', JsonErrorRenderer::class);
$errorHandler->forceContentType('application/json');

$app->get('/', DefaultController::class . ':index')->setName('index');
$app->get('/favicon.ico', DefaultController::class . ':favicon');

$app->group('/gam/{networkCode:[0-9]+}', function (RouteCollectorProxy $group) {
  $group->get('', function($req, $res, $args) {
    $data = ['networkCode' => intval($args['networkCode'])];
    return SlimUtil::writeJSON($res, ['data' => $data]);
  });
})->add(AdManagerServiceMiddleware::class);

$app->run();
