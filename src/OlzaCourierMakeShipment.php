<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\OlzaShipment;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Contracts\CourierMakeShipment;

class OlzaCourierMakeShipment implements CourierMakeShipment
{
	public function makeShipment() : Shipment
	{
		return new OlzaShipment();
	}
}
