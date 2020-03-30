<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\MockDependency;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\MockDependency\FixtureArgumentFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class FixtureArgumentFormatterTest extends TestCase
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
        $fixture = new FixtureArgumentFormatter();
        $result = $fixture->format($this->mockDependency);

        $this->assertSame(
            '$this->propertyName',
            $result
        );
    }

}
