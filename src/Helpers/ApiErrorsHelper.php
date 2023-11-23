<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Helpers;

use ArrayObject;

class ApiErrorsHelper
{
    public static function hasErrors(ArrayObject $errors): bool
    {
        return $errors->count() > 0;
    }

    public static function toArrayExceptions(ArrayObject $errors): array
    {
        $arr = [];
        $iterator = $errors->getIterator();
        for ($iterator; $iterator->valid(); $iterator->next()) {
            $arr[] = $iterator->current();
        }

        return $arr;
    }
}
