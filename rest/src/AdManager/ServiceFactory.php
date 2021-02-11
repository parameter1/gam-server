<?php
namespace Parameter1\AdManager;

use \Exception;
use Google\AdsApi\AdManager\AdManagerServices;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\AdsApi\Common\AdsSession;

class ServiceFactory {
  /**
   * @var Configuration
   */
  protected $config;

  /**
   * @var string
   */
  protected $networkCode;

  /**
   * @var AdsSession
   */
  protected $session;

  /**
   * @var AdManagerServices
   */
  protected $services;

  /**
   *
   */
  public function __construct(string $networkCode, string $jsonKeyFileRoot)
  {
    if (!$networkCode) throw new Exception('A network code is required to create the Ad Manager service factory.');
    if (!$jsonKeyFileRoot) throw new Exception('The JSON key file root is required to create the Ad Manager service factory.');
    $this->networkCode = $networkCode;

    $jsonKeyFilePath = $jsonKeyFileRoot . '/' . $networkCode . '.json';
    $this->config = new Configuration([
      'AD_MANAGER' => [
        'networkCode'     => $this->networkCode,
        'applicationName' => 'Parameter1 GAM REST API',
      ],
      'OAUTH2' => [
        'jsonKeyFilePath' =>  $jsonKeyFilePath,
        'scopes'          => 'https://www.googleapis.com/auth/dfp',
      ],
    ]);
    $this->session = (new AdManagerSessionBuilder())
      ->from($this->config)
      ->withOAuth2Credential($this->getCredentials())
      ->build()
    ;
  }

  /**
   *
   */
  public function service($class)
  {
    return $this->services->get($this->session, $class);
  }

  /**
   * @return ServiceAccountCredentials
   */
  private function getCredentials()
  {
    try {
      return (new OAuth2TokenBuilder())
        ->from($this->config)
        ->build()
      ;
    } catch (Exception $e) {
      if ($e->getMessage() === 'file does not exist') {
        throw new Exception('No Ad Manager service account file was found for network code ' . $this->networkCode);
      }
      throw $e;
    }
  }
}
