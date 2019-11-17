<?php

namespace Tests\Unit\GenSys\GenerateBundle\Model\Decorator;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Model\Decorator\SortedMethodCalls;

class SortedMethodCallsTest extends TestCase
{
    /** @var MethodCall[] */
    private $methodCalls;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->methodCalls = [
            new MethodCall('subjectA','methodA',[]),
            new MethodCall('subjectB','methodB',[]),
            new MethodCall('subjectA','methodC',[]),
            new MethodCall('subjectC','methodD',[]),
            new MethodCall('subjectB','methodE',[]),
        ];
        parent::__construct($name, $data, $dataName);
    }

    public function setUp(): void
    {
    }

    public function testShouldSort(): void
    {
        $expected = [
            new MethodCall('subjectA','methodA',[]),
            new MethodCall('subjectA','methodC',[]),
            new MethodCall('subjectB','methodB',[]),
            new MethodCall('subjectB','methodE',[]),
            new MethodCall('subjectC','methodD',[]),
        ];
        $fixture = new SortedMethodCalls($this->methodCalls);

        foreach ($fixture as $key => $item) {
            $this->assertEquals($expected[$key], $item);
        }

    }
}
