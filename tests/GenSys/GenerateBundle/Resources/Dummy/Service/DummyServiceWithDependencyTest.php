<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \GenSys\GenerateBundle\Resources\Dummy\Object\DummyObject;

class DummyServiceWithDependency extends TestCase
{
    /** @var DummyObject|MockObject */
    public $dummyObject;

    public function setUp()
    {
        $this->dummyObject = $this->getMockBuilder(DummyObject::class)->disableOriginalConstructor()->getMock();
    }

    public function testAddToDummyValueProperty()
    {
        $dummyObject = clone $this->dummyObject;
    }

    public function testAddToDummyValue()
    {
        $dummyObject = clone $this->dummyObject;
    }

}
