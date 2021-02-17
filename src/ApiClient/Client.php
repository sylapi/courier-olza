<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza\ApiClient;

use OlzaApiClient\Client as OlzaApiClient;
use Sylapi\Courier\Olza\ApiClient\Services\Transport;
use GuzzleHttp\Client as HttpClient;

class Client extends OlzaApiClient
{
    public function __construct(HttpClient $provider)
    {
        $this->transport = new Transport($provider);
    }
}