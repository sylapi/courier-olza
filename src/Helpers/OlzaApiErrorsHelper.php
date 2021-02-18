<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Helpers;

use ArrayObject;
use Exception;

class OlzaApiErrorsHelper
{
    const SEPARATOR = '|';

    /**
     * @param ArrayObject<string, Exception> $errors
     */
    public static function hasErrors(ArrayObject $errors): bool
    {
        return $errors->count() > 0;
    }

    /**
     * @param ArrayObject<string, Exception> $errors
     */
    public static function toString(ArrayObject $errors): string
    {
        $iterator = $errors->getIterator();
        $message = '';
        for ($iterator; $iterator->valid(); $iterator->next()) {
            $message .= (strlen($message) > 0) ? self::SEPARATOR : '';
            $message .= $iterator->key().' => '.$iterator->current()->getMessage();
        }

        return $message;
    }
}
