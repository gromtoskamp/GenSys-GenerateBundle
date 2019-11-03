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

    public function testAddToDummyValueDirect(): void
    {
        $this->dummyObject->method('getDummyValue')->willReturn(null);
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $this->tearDown();
    }

    public function testAddToDummyValueProperty(): void
    {
        $this->dummyObjectA->method('getDummyValue')->willReturn(null);
        $this->dummyObjectB->method('getDummyValue')->willReturn(null);
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $this->tearDown();
    }

    public function testAddToDummyValue(): void
    {
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $this->tearDown();
    }

    public function testAddToMultipleDummyValues(): void
    {
        $fixture = new DummyServiceWithDependency($this->dummyObjectA, $this->dummyObjectB);
        $this->tearDown();
    }

    public function tearDown(): void
    {
        unset($this->dummyObjectA);
        unset($this->dummyObjectB);
        unset($this->dummyObject);
    }
}
