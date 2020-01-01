<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\ConstructorArgumentFormatter;
use GenSys\GenerateBundle\Model\PropertyType;

class ConstructorArgumentFormatterTest extends TestCase
{
    /** @var PropertyType|MockObject */
    public $propertyType;

    public function setUp(): void
    {
        $this->propertyType = $this->getMockBuilder(PropertyType::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormat(): void
    {
        $this->propertyType->method('getPropertyName')->willReturn('testPropertyName');
        $fixture = new ConstructorArgumentFormatter();
        $result = $fixture->format($this->propertyType);

        $this->assertSame(
            '$testPropertyName',
            $result
        );
    }

}
