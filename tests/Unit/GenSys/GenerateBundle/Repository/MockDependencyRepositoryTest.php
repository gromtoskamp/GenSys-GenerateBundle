<?php

namespace Tests\Unit\GenSys\GenerateBundle\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Repository\MockDependencyRepository;
use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionMethod;

class MockDependencyRepositoryTest extends TestCase
{
    /** @var MockDependency|MockObject */
    public $mockDependency;

    /** @var ReflectionMethod|MockObject */
    public $reflectionMethod;


    public function setUp(): void
    {
        $this->mockDependency = $this->getMockBuilder(MockDependency::class)->disableOriginalConstructor()->getMock();
        $this->reflectionMethod = $this->getMockBuilder(ReflectionMethod::class)->disableOriginalConstructor()->getMock();
    }

    public function testAddtestGetAll(): void
    {
        $this->reflectionMethod->method('getName')->willReturn('testName');
        $this->mockDependency->method('getClassName')->willReturn('testClassName');
        $fixture = new MockDependencyRepository();

        $this->assertEmpty($fixture->getAll());
        $fixture->add($this->mockDependency,$this->reflectionMethod);
        $this->assertSame(
            $this->mockDependency,
            $fixture->getAll()['testClassName']
        );
    }

    public function testGetByPropertyCall(): void
    {
        $fixture = new MockDependencyRepository();
        $this->mockDependency->method('getPropertyName')->willReturn('className');
        $fixture->add($this->mockDependency, $this->reflectionMethod);
        $result = $fixture->getByPropertyCall('className');

        $this->assertSame(
            $this->mockDependency,
            $result
        );
    }

    public function testGetByReflectionMethod(): void
    {
        $fixture = new MockDependencyRepository();
        $this->reflectionMethod->method('getName')->willReturn('testName');

        $fixture->add($this->mockDependency, $this->reflectionMethod);
        $result = $fixture->getByReflectionMethod($this->reflectionMethod);

        $this->assertSame(
            $this->mockDependency,
            $result[0]
        );
    }
}
