<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\TestMethodCallFormatter;
use GenSys\GenerateBundle\Model\MethodCall;

class TestMethodCallFormatterTest extends TestCase
{
    /** @var MethodCall|MockObject */
    public $methodCall;

    public function setUp(): void
    {
        $this->methodCall = $this->getMockBuilder(MethodCall::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormat(): void
    {
        $this->methodCall->method('getSubject')->willReturn('subject');
        $this->methodCall->method('getMethodName')->willReturn('methodName');
        $fixture = new TestMethodCallFormatter();
        $result = $fixture->format($this->methodCall);

        $this->assertSame(
            '$this->subject->method(\'methodName\')->willReturn(null);',
            $result
        );
    }

}
