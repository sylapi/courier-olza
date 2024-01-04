<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\CourierGetStatuses;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Olza\Tests\Helpers\SessionTrait;

class CourierGetStatusesTest extends PHPUnitTestCase
{
    use SessionTrait;

    public function testGetStatusSuccess(): void
    {
        $courierGetStatuses = new CourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusSuccess.json']
            )
        );

        $response = $courierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::SENT->value);
    }

    public function testGetStatusFailure(): void
    {
        $olzaCourierGetStatuses = new CourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusFailure.json']
            )
        );

        $this->expectException(TransportException::class);
        $olzaCourierGetStatuses->getStatus('123');
    }
}
