<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\MockDependency;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\MockDependency\InitMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class InitMockDependencyFormatterTest extends TestCase
{
    /** @var MockDependency|MockObject */
    public $mockDependency;

    public function setUp(): void
    {
        $this->mockDependency = $this->createMock(MockDependency::class);
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getPropertyName')->willReturn('propertyName');
        $this->mockDependency->method('getClassName')->willReturn('ClassName');
        $fixture = new InitMockDependencyFormatter();
        $result = $fixture->format($this->mockDependency);

        $this->assertSame(
            '$this->propertyName = $this->createMock(ClassName::class);',
            $result
        );
    }
}
