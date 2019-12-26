<?php

namespace Tests\Unit\GenSys\GenerateBundle\Service\Decorator;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Service\Decorator\MethodCallSorter;

class MethodCallSorterTest extends TestCase
{
    /** @var MethodCall */
    private $methodCallAA;
    /** @var MethodCall */
    private $methodCallAB;
    /** @var MethodCall */
    private $methodCallBA;
    /** @var MethodCall */
    private $methodCallBB;

    /**
     * MethodCallSorterTest constructor.
     */
    public function __construct(
    ) {
        $this->methodCallAA = new MethodCall('testSubjectA','methodNameA');
        $this->methodCallAB = new MethodCall('testSubjectA','methodNameB');
        $this->methodCallBA = new MethodCall('testSubjectB','methodNameA');
        $this->methodCallBB = new MethodCall('testSubjectB','methodNameB');
        parent::__construct();
    }

    public function testGroupBySubject(): void
    {
        $testArray = [
            $this->methodCallAA,
            $this->methodCallBB,
            $this->methodCallBA,
            $this->methodCallAB,
        ];
        $fixture = new MethodCallSorter();
        /** @var MethodCall[] $result */
        $result = $fixture->decorate($testArray);

        $this->assertSame(
            $result[0]->getSubject(),
            $result[1]->getSubject()
        );
        $this->assertSame(
            $result[2]->getSubject(),
            $result[3]->getSubject()
        );
    }

}
