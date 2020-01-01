<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\PropertyType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\ConstructorArgumentsFormatter;
use GenSys\GenerateBundle\Formatter\ConstructorArgumentFormatter;

class ConstructorArgumentsFormatterTest extends TestCase
{
    /** @var ConstructorArgumentFormatter|MockObject */
    public $constructorArgumentFormatter;


    public function setUp(): void
    {
        $this->constructorArgumentFormatter = $this->getMockBuilder(ConstructorArgumentFormatter::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormat(): void
    {
        $this->constructorArgumentFormatter->method('format')->willReturn('');
        $fixture = new ConstructorArgumentsFormatter($this->constructorArgumentFormatter);
        $emptyPropertyType = new PropertyType('', '');
        $result = $fixture->format([$emptyPropertyType, $emptyPropertyType, $emptyPropertyType]);

        $this->assertSame(
            ",\n            ,\n            ",
            $result
        );
    }

}
