<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\UseMockDependenciesFormatter;

class UseMockDependenciesFormatterTest extends TestCase
{

    /** @var MockObject */
    private $mockDependency;

    public function setUp(): void
    {
        $this->mockDependency = $this->getMockBuilder(MockDependency::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormatEmpty(): void
    {
        $fixture = new UseMockDependenciesFormatter();
        $result = $fixture->format([]);

        $this->assertSame(
            '',
            $result
        );
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getFullyQualifiedClassName')->willReturn('fullyQualifiedName');

        $fixture = new UseMockDependenciesFormatter();
        $result = $fixture->format([$this->mockDependency, $this->mockDependency]);

        $this->assertSame(
            "use fullyQualifiedName;\nuse fullyQualifiedName;",
            $result
        );
    }
}
