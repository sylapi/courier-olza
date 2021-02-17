<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierMakeBooking;

class OlzaCourierMakeBooking implements CourierMakeBooking
{
	public function makeBooking() : Booking
	{
		return new OlzaBooking();
	}
}
