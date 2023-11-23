<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierMakeShipment as CourierMakeShipmentContract;
use Sylapi\Courier\Contracts\Shipment as ShipmentContract;

class CourierMakeShipment implements CourierMakeShipmentContract
{
    public function makeShipment(): ShipmentContract
    {
        return new Shipment();
    }
}
