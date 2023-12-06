<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use Sylapi\Courier\Contracts\Label;
use Sylapi\Courier\Olza\CourierGetLabels;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Olza\Tests\Helpers\SessionTrait;

class CourierGetLabelsTest extends PHPUnitTestCase
{
    use SessionTrait;

    public function testGetLabelsSuccess(): void
    {
        $olzaCourierGetLabels = new CourierGetLabels(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetLabelsSuccess.json']
            )
        );

        $response = $olzaCourierGetLabels->getLabel('123');
        $this->assertEquals($response, 'JVBERi0xLjcKOCAwIG9iago8PCAv');
    }

    public function testGetLabelsFailure(): void
    {
        $olzaCourierGetLabels = new CourierGetLabels(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierGetLabelsFailure.json']
            )
        );

        $this->expectException(TransportException::class);
        $olzaCourierGetLabels->getLabel('123');
    }
}
