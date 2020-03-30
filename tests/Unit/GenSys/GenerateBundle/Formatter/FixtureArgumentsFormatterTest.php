<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\FixtureArgumentsFormatter;


class FixtureArgumentsFormatterTest extends TestCase
{
    /** @var FixtureArgumentsFormatter */
    private $fixture;
    /** @var MockObject */
    private $mockDependency;

    public function setUp(): void
    {
        $this->fixture = new FixtureArgumentsFormatter();
        $this->mockDependency = $this->createMock(MockDependency::class);
    }

    public function testFormat(): void
    {
        $this->mockDependency->method('getPropertyName')->willReturn('propertyName');
        $mockDependencies = [$this->mockDependency];
        $result = $this->fixture->format($mockDependencies);

        $this->assertSame(
            '$this->propertyName',
            $result
        );
    }

}
