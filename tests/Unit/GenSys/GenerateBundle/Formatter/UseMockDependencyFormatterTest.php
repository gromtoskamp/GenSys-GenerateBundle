<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\UseMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class UseMockDependencyFormatterTest extends TestCase
{
    /** @var MockDependency|MockObject */
    public $mockDependency;

    public function setUp(): void
    {
        $this->mockDependency = $this->createMock(MockDependency::class);
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getFullyQualifiedClassName')->willReturn('fullyQualifiedClassName');
        $fixture = new UseMockDependencyFormatter();
        $result = $fixture->format($this->mockDependency);

        $this->assertSame(
            'use fullyQualifiedClassName;',
            $result
        );
    }
}
