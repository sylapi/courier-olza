<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierMakeReceiver as CourierMakeReceiverContract;
use Sylapi\Courier\Contracts\Receiver as ReceiverContract;

class CourierMakeReceiver implements CourierMakeReceiverContract
{
    public function makeReceiver(): ReceiverContract
    {
        return new Receiver();
    }
}
