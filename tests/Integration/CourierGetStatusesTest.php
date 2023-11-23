<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\CourierGetStatuses;
use Sylapi\Courier\Olza\Tests\Helpers\SessionTrait;

class CourierGetStatusesTest extends PHPUnitTestCase
{
    use SessionTrait;

    public function testGetStatusSuccess(): void
    {
        $olzaCourierGetStatuses = new CourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusSuccess.json']
            )
        );

        $response = $olzaCourierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::SENT->value);
    }

    public function testGetStatusFailure(): void
    {
        $olzaCourierGetStatuses = new CourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusFailure.json']
            )
        );

        $response = $olzaCourierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::APP_RESPONSE_ERROR->value);
        $this->assertTrue($response->hasErrors());
    }
}
