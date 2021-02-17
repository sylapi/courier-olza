<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierMakeParcel;
use Sylapi\Courier\Contracts\Parcel;

class OlzaCourierMakeParcel implements CourierMakeParcel
{
    public function makeParcel(): Parcel
    {
        return new OlzaParcel();
    }
}
