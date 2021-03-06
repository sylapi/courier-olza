<?php

namespace Sylapi\Courier\Olza\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Olza\OlzaParcel;
use Sylapi\Courier\Olza\OlzaReceiver;
use Sylapi\Courier\Olza\OlzaSender;
use Sylapi\Courier\Olza\OlzaShipment;

class OlzaShipmentTest extends PHPUnitTestCase
{
    public function testNumberOfPackagesIsAlwaysEqualTo1(): void
    {
        $parcel = new OlzaParcel();
        $shipment = new OlzaShipment();
        $shipment->setParcel($parcel);
        $shipment->setParcel($parcel);

        $this->assertEquals(1, $shipment->getQuantity());
    }

    public function testShipmentValidate(): void
    {
        $receiver = new OlzaReceiver();
        $sender = new OlzaSender();
        $parcel = new OlzaParcel();

        $shipment = new OlzaShipment();
        $shipment->setSender($sender)
            ->setReceiver($receiver)
            ->setParcel($parcel);

        $this->assertIsBool($shipment->validate());
        $this->assertIsBool($shipment->getReceiver()->validate());
        $this->assertIsBool($shipment->getSender()->validate());
        $this->assertIsBool($shipment->getParcel()->validate());
    }
}
