<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Enums;



enum ShipmentType :string {
    case DIRECT = 'DIRECT';
    case WAREHOUSE = 'WAREHOUSE';
}