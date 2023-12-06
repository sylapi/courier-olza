<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Olza\Entities\Credentials;
use OlzaApiClient\Entities\Helpers\HeaderEntity;
use OlzaApiClient\Entities\Request\ApiBatchRequest;
use Sylapi\Courier\Olza\ApiClient\Client as OlzaClient;

class Session
{
    private $credentials;
    private $request;
    private $header;
    private $client;
    private $httpClient;

    public function __construct(Credentials $credentials, HttpClient $httpClient)
    {
        $this->credentials = $credentials;
        $this->httpClient = $httpClient;
        $this->client = null;
        $this->request = null;
        $this->header = null;
    }

    public function client(): OlzaClient
    {
        if (!$this->client) {
            $this->client = $this->initializeSession();
        }

        return $this->client;
    }

    private function initializeSession(): OlzaClient
    {
        $this->client = new OlzaClient($this->httpClient);
        return $this->client;
    }

    public function request(): ApiBatchRequest
    {
        if (!$this->request) {
            $this->request = $this->initializeRequest();
        }

        return $this->request;
    }

    private function initializeRequest(): ApiBatchRequest
    {
        $apiRequest = new ApiBatchRequest();
        $this->request = $apiRequest->setHeaderFromHelper($this->header());
        return $this->request;
    }

    public function header(): HeaderEntity
    {
        if (!$this->header) {
            $this->header = $this->initializeHeader();
        }

        return $this->header;
    }

    private function initializeHeader(): HeaderEntity
    {
        $header = new HeaderEntity();
        $header->setApiUser($this->credentials->getLogin());
        $header->setApiPassword($this->credentials->getPassword());
        $this->header = $header->setLanguage($this->credentials->getLanguageCode());

        return $this->header;
    }
}
