<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\FixtureFormatter;
use GenSys\GenerateBundle\Formatter\MockDependencies\FixtureArgumentsFormatter;
use GenSys\GenerateBundle\Model\Fixture;

class FixtureFormatterTest extends TestCase
{
    /** @var FixtureArgumentsFormatter|MockObject */
    public $fixtureArgumentsFormatter;

    /** @var Fixture|MockObject */
    public $fixture;
    /** @var MockObject|MockDependency */
    private $mockDependency;
    /** @var FixtureFormatter */
    private $fixtureFormatter;

    public function setUp(): void
    {
        $this->fixtureArgumentsFormatter = $this->getMockBuilder(FixtureArgumentsFormatter::class)->disableOriginalConstructor()->getMock();
        $this->fixture = $this->getMockBuilder(Fixture::class)->disableOriginalConstructor()->getMock();
        $this->mockDependency = $this->createMock(MockDependency::class);

        $this->fixtureFormatter = new FixtureFormatter($this->fixtureArgumentsFormatter);
    }

    public function testFormat(): void
    {
        $this->fixtureArgumentsFormatter->method('format')->willReturn('formatted');
        $this->fixture->method('getClassName')->willReturn('ClassName');
        $result = $this->fixtureFormatter->format($this->fixture);

        $this->assertSame(
            '$this->fixture = new ClassName(formatted);',
            $result
        );
    }

    public function testFormatProperty(): void
    {
        $this->fixture->method('getClassName')->willReturn('ClassName');
        $result = $this->fixtureFormatter->formatProperty($this->fixture);

        $this->assertSame(
            '    /** @var ClassName $fixture */' . PHP_EOL . '    private $fixture;',
            $result
        );
    }

}
