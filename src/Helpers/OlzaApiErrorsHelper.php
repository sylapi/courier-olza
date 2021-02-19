<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Helpers;

use ArrayObject;

class OlzaApiErrorsHelper
{
    const SEPARATOR = '|';

    public static function hasErrors(ArrayObject $errors): bool
    {
        return $errors->count() > 0;
    }

    public static function toString(ArrayObject $errors): string
    {
        $iterator = $errors->getIterator();
        $messages = [];
        for ($iterator; $iterator->valid(); $iterator->next()) {
            $messages[] = $iterator->current()->getMessage();
        }

        return implode(self::SEPARATOR, $messages);
    }
}
