<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;


use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Olza\ApiClient\Services\Transport;
use Sylapi\Courier\Olza\Entities\Credentials;

class SessionFactory
{
    private $sessions = [];

    //These constants can be extracted into injected configuration
    const API_LIVE = 'https://panel.olzalogistic.com';
    const API_SANDBOX = 'https://test.panel.olzalogistic.com';

    public function session(Credentials $credentials): Session
    {
        $apiUrl = $credentials->isSandbox() ? self::API_SANDBOX : self::API_LIVE;

        $credentials->setApiUrl($apiUrl);

        $key = sha1( $apiUrl.':'.$credentials->getLogin().':'.$credentials->getPassword());

        $httpClient = new HttpClient([
            'base_uri' => $apiUrl.Transport::getApiCallUri(),
        ]);

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new Session($credentials, $httpClient));
    }
}
