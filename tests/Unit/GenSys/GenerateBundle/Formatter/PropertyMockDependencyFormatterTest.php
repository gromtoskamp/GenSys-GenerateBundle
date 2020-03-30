<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\PropertyMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class PropertyMockDependencyFormatterTest extends TestCase
{
    /** @var MockDependency|MockObject */
    public $mockDependency;

    public function setUp(): void
    {
        $this->mockDependency = $this->getMockBuilder(MockDependency::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getClassName')->willReturn('ClassName');
        $this->mockDependency->method('getPropertyName')->willReturn('propertyName');
        $fixture = new PropertyMockDependencyFormatter();
        $result = $fixture->format($this->mockDependency);

        $this->assertSame(
            '    /** @var ClassName|MockObject */' . PHP_EOL . '    public $propertyName;',
            $result
        );
    }

}
