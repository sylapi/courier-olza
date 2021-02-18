<?php

namespace Sylapi\Courier\Olza\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Exceptions\ResponseException;
use Sylapi\Courier\Olza\OlzaBooking;
use Sylapi\Courier\Olza\OlzaCourierPostShipment;
use Sylapi\Courier\Olza\Tests\Helpers\OlzaSessionTrait;

class OlzaCourierPostShipmentTest extends PHPUnitTestCase
{
    use OlzaSessionTrait;

    public function testPostShipmentSuccess(): void
    {
        $olzaCourierPostShipment = new OlzaCourierPostShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierPostShipmentSuccess.json']
            )
        );

        $shipmentId = '123';

        $booking = new OlzaBooking();
        $booking->setShipmentId($shipmentId);
        $response = $olzaCourierPostShipment->postShipment($booking);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('shipmentId', $response);
        $this->assertEquals($response['shipmentId'], $shipmentId);
        $this->assertArrayHasKey('trackingId', $response);
        $this->assertEquals($response['trackingId'], '00014459331');
        $this->assertArrayHasKey('trackingId', $response);
        $this->assertEquals($response['trackingBarcode'], '000144593313');
    }

    public function testPostShipmentFailure(): void
    {
        $this->expectException(ResponseException::class);

        $olzaCourierPostShipment = new OlzaCourierPostShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierPostShipmentFailure.json']
            )
        );

        $shipmentId = '123';

        $booking = new OlzaBooking();
        $booking->setShipmentId($shipmentId);
        $olzaCourierPostShipment->postShipment($booking);
    }
}
