<?php
namespace Parameter1\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DefaultController
{
  /**
   * @param Request $req
   * @param Response $res
   */
  public function index(Request $req, Response $res)
  {
    $res->getBody()->write(json_encode(['ping' => 'pong']));
    return $res->withHeader('content-type', 'application/json');
  }

  /**
   * @param Request $req
   * @param Response $res
   */
  public function favicon(Request $req, Response $res)
  {
    return $res->withStatus(404);
  }
}
