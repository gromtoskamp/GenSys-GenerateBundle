<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\MockDependencies\FixtureArgumentsFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\FixtureArgumentFormatter;

class FixtureArgumentsFormatterTest extends TestCase
{
    /** @var FixtureArgumentFormatter|MockObject */
    public $fixtureArgumentFormatter;
    /** @var MockObject|MockDependency */
    private $mockDependency;

    public function setUp(): void
    {
        $this->fixtureArgumentFormatter = $this->createMock(FixtureArgumentFormatter::class);
        $this->mockDependency = $this->createMock(MockDependency::class);
    }

    public function testFormat(): void
    {
        $this->fixtureArgumentFormatter->method('format')->willReturn('formatted');
        $fixture = new FixtureArgumentsFormatter($this->fixtureArgumentFormatter);
        $result = $fixture->format([$this->mockDependency, $this->mockDependency]);

        $this->assertSame(
            'formatted, formatted',
            $result
        );

        $this->tearDown();
    }

}
