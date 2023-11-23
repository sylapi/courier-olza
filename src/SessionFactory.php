<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\Parameters;
use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Olza\ApiClient\Services\Transport;

class SessionFactory
{
    private $sessions = [];

    /**
     * @var null|Parameters<string,mixed>
     */
    private $parameters;

    //These constants can be extracted into injected configuration
    const API_LIVE = 'https://panel.olzalogistic.com';
    const API_SANDBOX = 'https://test.panel.olzalogistic.com';

    public function session(Parameters $parameters): Session
    {
        $this->parameters = $parameters;
        $this->parameters->apiUrl = ($this->parameters->sandbox) ? self::API_SANDBOX : self::API_LIVE;

        $key = sha1($this->parameters->apiUrl.':'.$this->parameters->login.':'.$this->parameters->password);

        $httpClient = new HttpClient([
            'base_uri' => $this->parameters->apiUrl.Transport::getApiCallUri(),
        ]);

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new Session($this->parameters, $httpClient));
    }
}
