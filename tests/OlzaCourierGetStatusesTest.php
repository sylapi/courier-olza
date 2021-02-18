<?php

namespace Sylapi\Courier\Olza\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\OlzaCourierGetStatuses;
use Sylapi\Courier\Olza\Tests\Helpers\OlzaSessionTrait;

class OlzaCourierGetStatusesTest extends PHPUnitTestCase
{
    use OlzaSessionTrait;

    public function testGetStatusSuccess(): void
    {
        $olzaCourierGetStatuses = new OlzaCourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusSuccess.json']
            )
        );

        $response = $olzaCourierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::SENT);
    }

    public function testGetStatusFailure(): void
    {
        $olzaCourierGetStatuses = new OlzaCourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusFailure.json']
            )
        );

        $response = $olzaCourierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::APP_RESPONSE_ERROR);
        $this->assertTrue($response->hasErrors());
    }
}
