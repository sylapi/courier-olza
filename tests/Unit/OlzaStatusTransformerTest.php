<?php

namespace Sylapi\Courier\Olza\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Olza\OlzaStatusTransformer;

class OlzaStatusTransformerTest extends PHPUnitTestCase
{
    public function testStatusTransformer(): void
    {
        $olzaStatusTransformer = new OlzaStatusTransformer('STORNO');
        $this->assertEquals(StatusType::CANCELLED, (string) $olzaStatusTransformer);
    }
}
