<?php

namespace Sylapi\Courier\Olza\Tests;

use Sylapi\Courier\Olza\OlzaBooking;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Olza\OlzaCourierPostShipment;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
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

        $this->assertInstanceOf(Response::class, $response);
        $this->assertObjectHasAttribute('shipmentId', $response);
        $this->assertEquals($response->shipmentId, $shipmentId);
        $this->assertObjectHasAttribute('trackingId', $response);
        $this->assertEquals($response->trackingId, '00014459331');
        $this->assertObjectHasAttribute('trackingBarcode', $response);
        $this->assertEquals($response->trackingBarcode, '000144593313');
    }

    public function testPostShipmentFailure(): void
    {
        $olzaCourierPostShipment = new OlzaCourierPostShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierPostShipmentFailure.json']
            )
        );

        $shipmentId = '123';
        $booking = new OlzaBooking();
        $booking->setShipmentId($shipmentId);
        $response = $olzaCourierPostShipment->postShipment($booking);
        $this->assertTrue($response->hasErrors());
    }
}
