<?php

namespace Sylapi\Courier\Olza\Tests\Unit;

use ArrayObject;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;

class OlzaApiErrorsHelperTest extends PHPUnitTestCase
{
    public function testErrorsToString(): void
    {
        $messages = ['TEST 1', 'TEST 2'];
        $errors = [];

        foreach ($messages as $message) {
            $errors[] = new Exception($message);
        }

        $arrayObj = new ArrayObject($errors);
        $result = OlzaApiErrorsHelper::toString($arrayObj);

        $expected = implode(OlzaApiErrorsHelper::SEPARATOR, $messages);
        $this->assertEquals($expected, $result);
    }
}
