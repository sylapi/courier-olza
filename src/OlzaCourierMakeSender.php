<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierMakeSender;
use Sylapi\Courier\Contracts\Sender;

class OlzaCourierMakeSender implements CourierMakeSender
{
    public function makeSender(): Sender
    {
        return new OlzaSender();
    }
}
