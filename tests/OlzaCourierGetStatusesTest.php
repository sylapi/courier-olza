<?php

namespace Sylapi\Courier\Olza\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\OlzaCourierGetStatuses;
use Sylapi\Courier\Olza\Tests\Helpers\OlzaSessionTrait;

class OlzaCourierGetStatusesTest extends PHPUnitTestCase
{
    use OlzaSessionTrait;

    public function testGetStatusSuccess()
    {
        $olzaCourierGetStatuses = new OlzaCourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusSuccess.json']
            )
        );

        $response = $olzaCourierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::SENT);
    }

    public function testGetStatusFailure()
    {
        $olzaCourierGetStatuses = new OlzaCourierGetStatuses(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetStatusFailure.json']
            )
        );

        $response = $olzaCourierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::APP_RESPONSE_ERROR);
    }
}
