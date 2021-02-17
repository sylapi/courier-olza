<?php

namespace Sylapi\Courier\Olza\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\OlzaStatusTransformer;

class OlzaStatusTransformerTest extends PHPUnitTestCase
{
    public function testStatusTransformer()
    {
        $olzaStatusTransformer = new OlzaStatusTransformer('STORNO');
        $this->assertEquals(StatusType::CANCELLED, (string) $olzaStatusTransformer);
    }
}
