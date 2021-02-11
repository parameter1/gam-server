<?php
namespace Parameter1\Error;

use Slim\Exception\HttpException;
use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

/**
 * Custom application JSON Error Renderer
 */
class JsonErrorRenderer implements ErrorRendererInterface
{
  /**
   * @param Throwable $exception
   * @param bool      $displayErrorDetails
   * @return string
   */
  public function __invoke(Throwable $exception, bool $displayErrorDetails): string
  {
    $status = $exception instanceof HttpException ? $exception->getCode() : 500;
    $error = [
      'status' => $status,
      'message' => $exception->getMessage(),
    ];

    return (string) json_encode(['error' => $error], JSON_UNESCAPED_SLASHES);
  }
}
