<?php

namespace Sylapi\Courier\Olza\Tests;

use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\OlzaStatusTransformer;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class OlzaStatusTransformerTest extends PHPUnitTestCase
{
    public function testStatusTransformer()
    {   
        $olzaStatusTransformer = new OlzaStatusTransformer('STORNO');
        $this->assertEquals(StatusType::CANCELLED, (string) $olzaStatusTransformer);
    }
}
