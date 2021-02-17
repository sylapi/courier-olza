<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\Receiver;

class OlzaReceiver extends Receiver
{
    public function validate(): bool
    {
        return true;
    }
}
