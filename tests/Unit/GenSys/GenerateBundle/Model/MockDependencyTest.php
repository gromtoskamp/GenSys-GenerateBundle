<?php

namespace Tests\Unit\GenSys\GenerateBundle\Model;

use GenSys\GenerateBundle\Resources\Dummy\Service\DummyServiceWithDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionClass;
use ReflectionParameter;

class MockDependencyTest extends TestCase
{
    /** @var ReflectionParameter|MockObject */
    public $reflectionParameter;
    /** @var ReflectionParameter */
    private $parameter;


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $reflectionClass = new ReflectionClass(DummyServiceWithDependency::class);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();
        $this->parameter = $parameters[0];
        parent::__construct($name, $data, $dataName);
    }

    public function setUp(): void
    {
        $this->reflectionParameter = $this->getMockBuilder(ReflectionParameter::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetClassName(): void
    {
        $this->reflectionParameter->method('getClass')->willReturn(null);
        $fixture = new MockDependency($this->parameter);
        $result = $fixture->getClassName();

        $this->assertSame(
            'DummyObjectA',
            $result
        );
    }

    public function testGetPropertyName(): void
    {
        $this->reflectionParameter->method('getClass')->willReturn(null);
        $fixture = new MockDependency($this->parameter);
        $result = $fixture->getPropertyName();

        $this->assertSame(
            'dummyObjectA',
            $result
        );
    }

    public function testGetFullyQualifiedClassName(): void
    {
        $this->reflectionParameter->method('getClass')->willReturn(null);
        $fixture = new MockDependency($this->parameter);
        $result = $fixture->getFullyQualifiedClassName();

        $this->assertSame(
            'GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectA',
            $result
        );
    }

}
