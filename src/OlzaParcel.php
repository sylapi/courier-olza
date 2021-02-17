<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\Parcel;

class OlzaParcel extends Parcel
{
    public function validate(): bool
    {
        return true;
    }
}
