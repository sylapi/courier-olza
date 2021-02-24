<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Helpers;
use Sylapi\Courier\Exceptions\ValidateException;

class OlzaValidateErrorsHelper
{
    public static function hasErrors(array $errors): bool
    {
        return (count($errors) > 0);
    }

    public static function toArrayExceptions(array $errors): array
    {
        $arr = [];
        array_walk_recursive($errors, function($item) use(&$arr) {
            if (is_string($item)) {
                $arr[] = new ValidateException($item);
            }
        });
        return $arr;
    }
}
