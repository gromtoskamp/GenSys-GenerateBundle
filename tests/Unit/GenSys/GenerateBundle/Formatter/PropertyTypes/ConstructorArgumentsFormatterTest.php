<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\PropertyTypes;

use GenSys\GenerateBundle\Model\PropertyType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\PropertyTypes\ConstructorArgumentsFormatter;
use GenSys\GenerateBundle\Formatter\PropertyType\ConstructorArgumentFormatter;

class ConstructorArgumentsFormatterTest extends TestCase
{
    /** @var ConstructorArgumentFormatter|MockObject */
    public $constructorArgumentFormatter;
    /** @var ConstructorArgumentsFormatter */
    private $fixture;


    public function setUp(): void
    {
        $this->constructorArgumentFormatter = $this->getMockBuilder(ConstructorArgumentFormatter::class)->disableOriginalConstructor()->getMock();
        $this->fixture = new ConstructorArgumentsFormatter($this->constructorArgumentFormatter);
    }

    public function testFormat(): void
    {
        $this->constructorArgumentFormatter->method('format')->willReturn('');

        $emptyPropertyType = new PropertyType('', '');
        $result = $this->fixture->format([$emptyPropertyType, $emptyPropertyType, $emptyPropertyType]);

        $this->assertSame(
            ",\n            ,\n            ",
            $result
        );
    }

}
