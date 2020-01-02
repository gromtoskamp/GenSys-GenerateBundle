<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\PropertyMockDependencyFormatter;

class PropertyMockDependencyFormatterTest extends TestCase
{

    /** @var MockDependency|MockObject */
    private $mockDependency;

    public function setUp(): void
    {
        $this->mockDependency = $this->getMockBuilder(MockDependency::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormatEmpty(): void
    {
        $fixture = new PropertyMockDependencyFormatter();
        $result = $fixture->format([]);

        $this->assertSame(
            '',
            $result
        );
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getClassName')->willReturn('ClassName');
        $this->mockDependency->method('getPropertyName')->willReturn('propertyName');

        $fixture = new PropertyMockDependencyFormatter();
        $result = $fixture->format([$this->mockDependency, $this->mockDependency]);

        $this->assertSame(
            "    /** @var ClassName|MockObject */\n    public \$propertyName;\n\n    /** @var ClassName|MockObject */\n    public \$propertyName;",
            $result
        );

        $this->tearDown();
    }
}
