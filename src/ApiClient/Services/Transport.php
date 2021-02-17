<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza\ApiClient\Services;

use OlzaApiClient\Services\Transport as OlzaApiClientTransport;
use GuzzleHttp\Client as HttpClient;

class Transport extends OlzaApiClientTransport
{

    public function __construct(?HttpClient $provider = null)
    {
        $this->provider = $provider;
    }

    public static function getApiCallUri()
    {
        $self = new self();
        return $self->apiCallUri;
    }
}