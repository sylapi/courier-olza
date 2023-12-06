<?php

namespace Sylapi\Courier\Olza\Tests\Helpers;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Sylapi\Courier\Olza\Session;
use GuzzleHttp\Handler\MockHandler;
use Sylapi\Courier\Olza\Parameters;
use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Olza\Entities\Credentials;

trait SessionTrait
{
    /**
     * @param array<string> $responseMockFiles
     */
    private function getSession(array $responseMockFiles): Session
    {
        $responseMocks = [];

        foreach ($responseMockFiles as $mock) {
            $output = file_get_contents($mock);
            $responseMocks[] = new Response(200, [], $output);
        }

        $mock = new MockHandler($responseMocks);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);

        $credentials = new Credentials();
        $credentials
            ->setLanguageCode('pl')
            ->setLogin('login')
            ->setPassword('password')
            ->setSandbox(true)
            ;

        return  new Session($credentials, $client);
    }
}
