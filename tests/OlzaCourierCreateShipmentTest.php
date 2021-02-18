<?php

namespace Sylapi\Courier\Olza\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Exceptions\ResponseException;
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

        $shipmentMock->setSender($senderMock)
            ->setReceiver($receiverMock)
            ->setParcel($parcelMock)
            ->setContent('Description');

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

        $this->assertIsArray($response);
        $this->assertArrayHasKey('referenceId', $response);
        $this->assertArrayHasKey('shipmentId', $response);
        $this->assertEquals($response['shipmentId'], '123');
    }

    public function testCreateShipmentFailure(): void
    {
        $this->expectException(ResponseException::class);

        $olzaCourierCreateShipment = new OlzaCourierCreateShipment(
            $this->getSession(
                [__DIR__.'/Mock/OlzaCourierCreateShipmentFailure.json']
            )
        );

        $olzaCourierCreateShipment->createShipment($this->getShipmentMock());
    }
}
