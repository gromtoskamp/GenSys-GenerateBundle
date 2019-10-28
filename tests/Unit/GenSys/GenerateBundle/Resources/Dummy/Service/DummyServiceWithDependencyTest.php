<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectA;
use \GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectB;

class DummyServiceWithDependencyTest extends TestCase
{
    /** @var DummyObjectA|MockObject */
    public $dummyObjectA;

    /** @var DummyObjectB|MockObject */
    public $dummyObjectB;


    public function setUp()
    {
        $this->dummyObjectA = $this->getMockBuilder(DummyObjectA::class)->disableOriginalConstructor()->getMock();
        $this->dummyObjectB = $this->getMockBuilder(DummyObjectB::class)->disableOriginalConstructor()->getMock();
    }

    public function testAddToDummyValueProperty()
    {
        $dummyObjectA = clone $this->dummyObjectA;
        $dummyObjectB = clone $this->dummyObjectB;
        $dummyObjectA->expects($this->any())
            ->method('getDummyValue')
            ->willReturn(null);
        $dummyObjectB->expects($this->any())
            ->method('getDummyValue')
            ->willReturn(null);
    }

    public function testAddToDummyValue()
    {
        $dummyObjectB = clone $this->dummyObjectB;
    }

    public function testAddToMultipleDummyValues()
    {
        $dummyObjectA = clone $this->dummyObjectA;
        $dummyObjectB = clone $this->dummyObjectB;
    }

}
