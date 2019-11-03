<?php

namespace GenSys\GenerateBundle\Model;

class TestMethod
{
    /** @var string */
    private $name;
    /** @var MockDependency[] */
    private $mockDependencies;
    /** @var MethodCall[] */
    private $methodCalls;

    public function __construct(
        $name,
        array $mockDependencies,
        array $methodCalls
    ) {
        $this->name = $name;
        $this->mockDependencies = $mockDependencies;
        $this->methodCalls = $methodCalls;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return MockDependency[]
     */
    public function getMockDependencies(): array
    {
        return $this->mockDependencies;
    }

    /**
     * @return MethodCall[]
     */
    public function getMethodCalls(): array
    {
        return $this->methodCalls;
    }
}
