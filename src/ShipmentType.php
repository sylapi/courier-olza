<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\Enum;

class ShipmentType extends Enum
{
    const DIRECT = 'DIRECT'; // DIRECT
    const WAREHOUSE = 'WAREHOUSE'; // WAREHOUSE
}
