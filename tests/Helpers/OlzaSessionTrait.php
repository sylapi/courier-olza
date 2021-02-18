<?php

namespace Sylapi\Courier\Olza\Tests\Helpers;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Sylapi\Courier\Olza\OlzaParameters;
use Sylapi\Courier\Olza\OlzaSession;

trait OlzaSessionTrait
{
    /**
     * @param array<string> $responseMockFiles
     */
    private function getSession(array $responseMockFiles): OlzaSession
    {
        $responseMocks = [];

        foreach ($responseMockFiles as $mock) {
            $output = file_get_contents($mock);
            $responseMocks[] = new Response(200, [], $output);
        }

        $mock = new MockHandler($responseMocks);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);

        $parameters = OlzaParameters::create([
            'login'           => 'login',
            'password'        => 'password',
            'sandbox'         => true,
            'requestLanguage' => 'pl',
            'labelType'       => 'A4',
            'speditionCode'   => 'GLS',
            'shipmentType'    => 'WAREHOUSE',
        ]);

        return  new OlzaSession($parameters, $client);
    }
}
