<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\Sender;

class OlzaSender extends Sender
{
    public function validate(): bool
    {
        return true;
    }
}
