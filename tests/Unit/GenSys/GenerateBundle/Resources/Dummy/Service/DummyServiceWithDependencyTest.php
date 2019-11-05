<?php

namespace Tests\Unit\GenSys\GenerateBundle\Resources\Dummy\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Resources\Dummy\Service\DummyServiceWithDependency;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectA;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectB;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObject;

class DummyServiceWithDependencyTest extends TestCase
{
    /** @var DummyObjectA|MockObject */
    public $dummyObjectA;

    /** @var DummyObjectB|MockObject */
    public $dummyObjectB;

    /** @var DummyObject|MockObject */
    public $dummyObject;


    public function setUp(): void
    {
        $this->dummyObjectA = $this->getMockBuilder(DummyObjectA::class)->disableOriginalConstructor()->getMock();
        $this->dummyObjectB = $this->getMockBuilder(DummyObjectB::class)->disableOriginalConstructor()->getMock();
        $this->dummyObject = $this->getMockBuilder(DummyObject::class)->disableOriginalConstructor()->getMock();
    }

    public function testAddToDummyValue(): void
    {
        $this->dummyObjectB->method('getDummyValue')->willReturn(5);
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $result = $fixture->addToDummyValue($this->dummyObjectB,5);

        $this->assertSame(10, $result);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function testAddToDummyValueDirect(): void
    {
        $this->dummyObject->method('getDummyValue')->willReturn(5);
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $result = $fixture->addToDummyValueDirect($this->dummyObject,3);

        $this->assertSame(8, $result);

        $this->tearDown();
    }

    public function testAddToDummyValueProperty(): void
    {
        $this->dummyObjectA->expects($this->atLeastOnce())->method('getDummyValue')->willReturn(1);
        $this->dummyObjectB->expects($this->atLeastOnce())->method('getDummyValue')->willReturn(1);
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $result = $fixture->addToDummyValueProperty(1);

        $this->assertSame(1, $result);

        $this->tearDown();
    }

    public function testAddToMultipleDummyValues(): void
    {
        $this->dummyObjectA->method('getDummyValue')->willReturn(1);
        $this->dummyObjectB->method('getDummyValue')->willReturn(2);
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $result = $fixture->addToMultipleDummyValues($this->dummyObjectA,$this->dummyObjectB,3);

        $this->assertSame(9, $result);

        $this->tearDown();
    }

    public function tearDown(): void
    {
        unset($this->dummyObjectA);
        unset($this->dummyObjectB);
        unset($this->dummyObject);
    }
}
