<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierMakeShipment;
use Sylapi\Courier\Contracts\Shipment;

class OlzaCourierMakeShipment implements CourierMakeShipment
{
    public function makeShipment(): Shipment
    {
        return new OlzaShipment();
    }
}
