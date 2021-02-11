<?php
namespace Parameter1\Middleware;

use Parameter1\AdManager\ServiceFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

use function intval;

class AdManagerServiceMiddleware
{
    /**
     *
     * @param Request  $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $networkCode = $routeContext->getRoute()->getArgument('networkCode');

        $jsonKeyFileRoot = getenv('JSON_KEY_FILE_ROOT');
        if (!$jsonKeyFileRoot) throw new HttpInternalServerErrorException($request, 'No ad manager key file root has been specified for this server.');
        $serviceFactory = new ServiceFactory($networkCode, $jsonKeyFileRoot);

        $request = $request->withAttribute('gam', $serviceFactory);
        return $handler->handle($request);
    }
}
