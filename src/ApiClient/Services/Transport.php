<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\ApiClient\Services;

use GuzzleHttp\Client as HttpClient;
use OlzaApiClient\Services\Transport as OlzaApiClientTransport;

class Transport extends OlzaApiClientTransport
{
    public function __construct(?HttpClient $provider = null)
    {
        $this->provider = $provider;
    }

    public static function getApiCallUri(): string
    {
        $self = new self();

        return $self->apiCallUri;
    }
}
