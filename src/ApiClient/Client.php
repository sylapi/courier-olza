<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\ApiClient;

use GuzzleHttp\Client as HttpClient;
use OlzaApiClient\Client as OlzaApiClient;
use OlzaApiClient\Interfaces\TransportInterface;
use Sylapi\Courier\Olza\ApiClient\Services\Transport;

class Client extends OlzaApiClient
{
    /**
     * @var TransportInterface
     */
    protected $transport;

    public function __construct(HttpClient $provider)
    {
        $this->transport = new Transport($provider);
    }
}
