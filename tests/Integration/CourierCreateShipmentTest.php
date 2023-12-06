<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Olza\CourierCreateShipment;
use Sylapi\Courier\Olza\Entities\Parcel;
use Sylapi\Courier\Olza\Entities\Receiver;
use Sylapi\Courier\Olza\Entities\Sender;
use Sylapi\Courier\Olza\Entities\Shipment;
use Sylapi\Courier\Olza\Responses\Shipment as ResponsesShipment;
use Sylapi\Courier\Olza\Tests\Helpers\SessionTrait;

class CourierCreateShipmentTest extends PHPUnitTestCase
{
    use SessionTrait;

    private function getShipmentMock()
    {
        $shipmentMock = $this->createMock(Shipment::class);
        $senderMock = $this->createMock(Sender::class);
        $receiverMock = $this->createMock(Receiver::class);
        $parcelMock = $this->createMock(Parcel::class);

        $shipmentMock->method('getSender')->willReturn($senderMock);
        $shipmentMock->method('getReceiver')->willReturn($receiverMock);
        $shipmentMock->method('getParcel')->willReturn($parcelMock);
        $shipmentMock->method('getContent')->willReturn('Description');
        $shipmentMock->method('validate')->willReturn(true);

        return $shipmentMock;
    }

    public function testCreateShipmentSuccess(): void
    {
        $olzaCourierCreateShipment = new CourierCreateShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierCreateShipmentSuccess.json']
            )
        );

        $response = $olzaCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(ResponsesShipment::class, $response);
        $this->assertEquals($response->getShipmentId(), '123');
    }

    public function testCreateShipmentFailure(): void
    {
        $olzaCourierCreateShipment = new CourierCreateShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierCreateShipmentFailure.json']
            )
        );
        $this->expectException(TransportException::class);

        $olzaCourierCreateShipment->createShipment($this->getShipmentMock());

    }
}
