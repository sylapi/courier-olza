<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Olza\ApiClient\Services\Transport;

class OlzaSessionFactory
{
    private $sessions = [];

    /**
     * @var null|OlzaParameters<string,mixed>
     */
    private $parameters;

    //These constants can be extracted into injected configuration
    const API_LIVE = 'https://live.olza.test';
    const API_SANDBOX = 'https://test.panel.olzalogistic.com';

    public function session(OlzaParameters $parameters): OlzaSession
    {
        $this->parameters = $parameters;
        $this->parameters->apiUrl = ($this->parameters->sandbox) ? self::API_SANDBOX : self::API_LIVE;

        $key = sha1($this->parameters->apiUrl.':'.$this->parameters->login.':'.$this->parameters->password);

        $httpClient = new HttpClient([
            'base_uri' => $this->parameters->apiUrl.Transport::getApiCallUri(),
        ]);

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new OlzaSession($this->parameters, $httpClient));
    }
}
