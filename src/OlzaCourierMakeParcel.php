<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\Parcel;
use Sylapi\Courier\Contracts\CourierMakeParcel;

class OlzaCourierMakeParcel implements CourierMakeParcel
{
	public function makeParcel() : Parcel
	{
		return new OlzaParcel();
	}
}
