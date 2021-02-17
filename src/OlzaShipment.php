<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\Shipment;

class OlzaShipment extends Shipment
{
    public function getQuantity(): int
    {
        return 1;
    }

    public function validate(): bool
    {
        return true;
    }
}
