<?php

namespace Sylapi\Courier\Olza\Tests\Integration;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Olza\OlzaCourierCreateShipment;
use Sylapi\Courier\Olza\OlzaParcel;
use Sylapi\Courier\Olza\OlzaReceiver;
use Sylapi\Courier\Olza\OlzaSender;
use Sylapi\Courier\Olza\OlzaShipment;
use Sylapi\Courier\Olza\Tests\Helpers\OlzaSessionTrait;

class OlzaCourierCreateShipmentTest extends PHPUnitTestCase
{
    use OlzaSessionTrait;

    private function getShipmentMock()
    {
        $shipmentMock = $this->createMock(OlzaShipment::class);
        $senderMock = $this->createMock(OlzaSender::class);
        $receiverMock = $this->createMock(OlzaReceiver::class);
        $parcelMock = $this->createMock(OlzaParcel::class);

        $shipmentMock->method('getSender')->willReturn($senderMock);
        $shipmentMock->method('getReceiver')->willReturn($receiverMock);
        $shipmentMock->method('getParcel')->willReturn($parcelMock);
        $shipmentMock->method('getContent')->willReturn('Description');

        return $shipmentMock;
    }

    public function testCreateShipmentSuccess(): void
    {
        $olzaCourierCreateShipment = new OlzaCourierCreateShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierCreateShipmentSuccess.json']
            )
        );

        $response = $olzaCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertObjectHasAttribute('referenceId', $response);
        $this->assertObjectHasAttribute('shipmentId', $response);
        $this->assertEquals($response->shipmentId, '123');
        $this->assertTrue(true);
    }

    public function testCreateShipmentFailure(): void
    {
        $olzaCourierCreateShipment = new OlzaCourierCreateShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierCreateShipmentFailure.json']
            )
        );

        $response = $olzaCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->hasErrors());
        $this->assertInstanceOf(\Throwable::class, $response->getFirstError());
    }
}
