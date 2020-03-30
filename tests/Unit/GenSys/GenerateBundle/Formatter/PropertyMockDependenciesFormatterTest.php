<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Formatter\PropertyMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\PropertyMockDependenciesFormatter;

class PropertyMockDependenciesFormatterTest extends TestCase
{

    /** @var MockDependency|MockObject */
    private $mockDependency;
    /** @var MockObject|PropertyMockDependencyFormatter */
    private $propertyMockDependencyFormatter;
    /** @var PropertyMockDependenciesFormatter */
    private $fixture;

    public function setUp(): void
    {
        $this->propertyMockDependencyFormatter = $this->createMock(PropertyMockDependencyFormatter::class);
        $this->mockDependency = $this->getMockBuilder(MockDependency::class)->disableOriginalConstructor()->getMock();
        $this->fixture = new PropertyMockDependenciesFormatter($this->propertyMockDependencyFormatter);
    }

    public function testFormatEmpty(): void
    {

        $result = $this->fixture->format([]);

        $this->assertSame(
            '',
            $result
        );
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getClassName')->willReturn('ClassName');
        $this->mockDependency->method('getPropertyName')->willReturn('propertyName');

        $mockDependencies = [$this->mockDependency, $this->mockDependency];
        $this->propertyMockDependencyFormatter->expects($this->exactly(count($mockDependencies)))->method('format')->willReturn('formatted');

        $result = $this->fixture->format($mockDependencies);
        $this->assertSame(
            'formatted' . PHP_EOL . 'formatted',
            $result
        );

        $this->tearDown();
    }
}
