<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Formatter\ReturnTypeFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\TestMethodCallFormatter;
use GenSys\GenerateBundle\Model\MethodCall;

class TestMethodCallFormatterTest extends TestCase
{
    /** @var MethodCall|MockObject */
    public $methodCall;
    /** @var MockObject|ReturnTypeFormatter */
    private $returnTypeFormatter;

    public function setUp(): void
    {
        $this->methodCall = $this->getMockBuilder(MethodCall::class)->disableOriginalConstructor()->getMock();
        $this->returnTypeFormatter = $this->createMock(ReturnTypeFormatter::class);
    }

    public function testFormat(): void
    {
        $this->methodCall->method('getSubject')->willReturn('subject');
        $this->methodCall->method('getMethodName')->willReturn('methodName');
        $this->returnTypeFormatter->method('format')->willReturn('formatted');
        $fixture = new TestMethodCallFormatter($this->returnTypeFormatter);
        $result = $fixture->format($this->methodCall);

        $this->assertSame(
            '$this->subject->method(\'methodName\')->willReturn(formatted);',
            $result
        );
    }

}
