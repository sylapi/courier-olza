<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Olza\OlzaBooking;
use Sylapi\Courier\Olza\OlzaCourierPostShipment;
use Sylapi\Courier\Olza\Tests\Helpers\OlzaSessionTrait;

class OlzaCourierPostShipmentTest extends PHPUnitTestCase
{
    use OlzaSessionTrait;

    private function getBookingMock($shipmentId)
    {
        $bookingMock = $this->createMock(OlzaBooking::class);
        $bookingMock->method('getShipmentId')->willReturn($shipmentId);
        $bookingMock->method('validate')->willReturn(true);

        return $bookingMock;
    }

    public function testPostShipmentSuccess(): void
    {
        $olzaCourierPostShipment = new OlzaCourierPostShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierPostShipmentSuccess.json']
            )
        );

        $shipmentId = '123';
        $booking = $this->getBookingMock($shipmentId);
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
        $booking = $this->getBookingMock($shipmentId);
        $response = $olzaCourierPostShipment->postShipment($booking);
        $this->assertTrue($response->hasErrors());
    }
}
