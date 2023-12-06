<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use Sylapi\Courier\Olza\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Olza\Entities\Booking;
use Sylapi\Courier\Olza\CourierPostShipment;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
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

        $this->assertInstanceOf(ParcelResponse::class, $response);
        $this->assertEquals($response->getShipmentId(), $shipmentId);
        $this->assertEquals($response->getTrackingId(), '00014459331');
        $this->assertEquals($response->getTrackingBarcode(), '000144593313');
    }

    public function testPostShipmentFailure(): void
    {
        $olzaCourierPostShipment = new CourierPostShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierPostShipmentFailure.json']
            )
        );

        $shipmentId = '123';
        $this->expectException(TransportException::class);
        $booking = $this->getBookingMock($shipmentId);
        $olzaCourierPostShipment->postShipment($booking);
    }
}
