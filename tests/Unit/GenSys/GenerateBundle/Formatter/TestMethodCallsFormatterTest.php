<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MethodCall;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\TestMethodCallsFormatter;
use GenSys\GenerateBundle\Formatter\TestMethodCallFormatter;

class TestMethodCallsFormatterTest extends TestCase
{
    /** @var TestMethodCallFormatter|MockObject */
    public $testMethodCallFormatter;
    /** @var MockObject|MethodCall */
    private $methodCall;

    public function setUp(): void
    {
        $this->testMethodCallFormatter = $this->getMockBuilder(TestMethodCallFormatter::class)->disableOriginalConstructor()->getMock();
        $this->methodCall = $this->createMock(MethodCall::class);
    }

    public function testFormat(): void
    {
        $this->testMethodCallFormatter->method('format')->willReturn('formatted');
        $fixture = new TestMethodCallsFormatter($this->testMethodCallFormatter);
        $result = $fixture->format([$this->methodCall, $this->methodCall]);

        $this->assertSame(
            'formatted' . PHP_EOL . '        formatted',
            $result
        );

        $this->tearDown();
    }

}
