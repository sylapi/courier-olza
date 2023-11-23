<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Olza\Booking;
use Sylapi\Courier\Olza\CourierPostShipment;
use Sylapi\Courier\Olza\Tests\Helpers\SessionTrait;

class CourierPostShipmentTest extends PHPUnitTestCase
{
    use SessionTrait;

    private function getBookingMock($shipmentId)
    {
        $bookingMock = $this->createMock(Booking::class);
        $bookingMock->method('getShipmentId')->willReturn($shipmentId);
        $bookingMock->method('validate')->willReturn(true);

        return $bookingMock;
    }

    public function testPostShipmentSuccess(): void
    {
        $olzaCourierPostShipment = new CourierPostShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierPostShipmentSuccess.json']
            )
        );

        $shipmentId = '123';
        $booking = $this->getBookingMock($shipmentId);
        $response = $olzaCourierPostShipment->postShipment($booking);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertObjectHasProperty('shipmentId', $response);
        $this->assertEquals($response->shipmentId, $shipmentId);
        $this->assertObjectHasProperty('trackingId', $response);
        $this->assertEquals($response->trackingId, '00014459331');
        $this->assertObjectHasProperty('trackingBarcode', $response);
        $this->assertEquals($response->trackingBarcode, '000144593313');
    }

    public function testPostShipmentFailure(): void
    {
        $olzaCourierPostShipment = new CourierPostShipment(
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
