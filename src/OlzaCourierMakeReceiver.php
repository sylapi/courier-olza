<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\OlzaReceiver;
use Sylapi\Courier\Contracts\Receiver;
use Sylapi\Courier\Contracts\CourierMakeReceiver;

class OlzaCourierMakeReceiver implements CourierMakeReceiver
{
	public function makeReceiver(): Receiver
	{
		return new OlzaReceiver();
	}
}
