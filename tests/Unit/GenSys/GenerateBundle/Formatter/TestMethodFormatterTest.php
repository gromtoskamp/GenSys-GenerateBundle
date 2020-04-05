<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\Fixture;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\TestMethodFormatter;
use GenSys\GenerateBundle\Formatter\TestMethodCallsFormatter;
use GenSys\GenerateBundle\Formatter\FixtureFormatter;
use GenSys\GenerateBundle\Model\TestMethod;

class TestMethodFormatterTest extends TestCase
{
    /** @var TestMethodCallsFormatter|MockObject */
    public $testMethodCallsFormatter;
    /** @var FixtureFormatter|MockObject */
    public $fixtureFormatter;
    /** @var TestMethod|MockObject */
    public $testMethod;
    /** @var MockObject|Fixture */
    private $fixture;

    public function setUp(): void
    {
        $this->testMethodCallsFormatter = $this->getMockBuilder(TestMethodCallsFormatter::class)->disableOriginalConstructor()->getMock();
        $this->fixtureFormatter = $this->getMockBuilder(FixtureFormatter::class)->disableOriginalConstructor()->getMock();
        $this->fixture = $this->createMock(Fixture::class);
        $this->testMethod = $this->getMockBuilder(TestMethod::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormatTestMethodCalls(): void
    {
        $this->testMethodCallsFormatter->method('format')->willReturn('formatted');
        $this->testMethod->method('getMethodCalls')->willReturn([]);
        $fixture = new TestMethodFormatter($this->testMethodCallsFormatter, $this->fixtureFormatter);
        $result = $fixture->formatTestMethodCalls($this->testMethod);

        $this->assertSame(
            'formatted' . PHP_EOL,
            $result
        );
    }

    public function testFormatResult_returnsVoid(): void
    {
        $this->testMethod->method('isReturnsVoid')->willReturn(true);
        $this->testMethod->method('getOriginalName')->willReturn('originalName');
        $fixture = new TestMethodFormatter($this->testMethodCallsFormatter, $this->fixtureFormatter);
        $result = $fixture->formatResult($this->testMethod);

        $this->assertSame(
            '$this->fixture->originalName();' . PHP_EOL,
            $result
        );
    }

    public function testFormatResult_returnsSomething(): void
    {
        $this->testMethod->method('isReturnsVoid')->willReturn(false);
        $this->testMethod->method('getOriginalName')->willReturn('originalName');
        $fixture = new TestMethodFormatter($this->testMethodCallsFormatter, $this->fixtureFormatter);
        $result = $fixture->formatResult($this->testMethod);

        $this->assertSame(
            '$result = $this->fixture->originalName();' . PHP_EOL,
            $result
        );
    }

}
