<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use GuzzleHttp\Client as HttpClient;
use OlzaApiClient\Entities\Helpers\HeaderEntity;
use OlzaApiClient\Entities\Request\ApiBatchRequest;
use Sylapi\Courier\Olza\ApiClient\Client as OlzaClient;

class OlzaSession
{
    private $parameters;
    private $request;
    private $header;
    private $client;
    private $httpClient;

    public function __construct(OlzaParameters $parameters, HttpClient $httpClient)
    {
        $this->parameters = $parameters;
        $this->httpClient = $httpClient;
        $this->initParameters();
        $this->client = null;
        $this->request = null;
        $this->header = null;
    }

    public function parameters(): OlzaParameters
    {
        return $this->parameters;
    }

    private function initParameters(): void
    {
        $this->parameters()->requestLanguage = $this->parameters()->requestLanguage ?? HeaderEntity::LANG_PL;
    }

    public function client(): OlzaClient
    {
        if (!$this->client) {
            $this->initializeSession();
        }

        return $this->client;
    }

    private function initializeSession(): void
    {
        $this->client = new OlzaClient($this->httpClient);
    }

    public function request(): ApiBatchRequest
    {
        if (!$this->request) {
            $this->initializeRequest();
        }

        return $this->request;
    }

    private function initializeRequest(): void
    {
        $apiRequest = new ApiBatchRequest();
        $this->request = $apiRequest->setHeaderFromHelper($this->header());
    }

    public function header(): HeaderEntity
    {
        if (!$this->header) {
            $this->initializeHeader();
        }

        return $this->header;
    }

    private function initializeHeader(): void
    {
        $header = new HeaderEntity();
        $header->setApiUser($this->parameters()->login);
        $header->setApiPassword($this->parameters()->password);
        $this->header = $header->setLanguage($this->parameters()->requestLanguage);
    }
}
