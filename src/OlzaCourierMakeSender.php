<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\OlzaSender;
use Sylapi\Courier\Contracts\Sender;
use Sylapi\Courier\Contracts\CourierMakeSender;

class OlzaCourierMakeSender implements CourierMakeSender
{
	public function makeSender(): Sender
	{
		return new OlzaSender();
	}
}
