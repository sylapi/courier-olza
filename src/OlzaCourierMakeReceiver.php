<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierMakeReceiver;
use Sylapi\Courier\Contracts\Receiver;

class OlzaCourierMakeReceiver implements CourierMakeReceiver
{
    public function makeReceiver(): Receiver
    {
        return new OlzaReceiver();
    }
}
