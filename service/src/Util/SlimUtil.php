<?php
namespace Parameter1\Util;

use Psr\Http\Message\ResponseInterface as Response;

class SlimUtil {
  /**
   *
   */
  private function __construct() {}

  /**
   * @param Response $response
   * @param mixed $data
   * @return Response
   */
  public static function writeJSON(Response $response, $data): Response
  {
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('content-type', 'application/json');
  }
}
