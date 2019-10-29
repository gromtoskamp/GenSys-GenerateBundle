<?php

namespace GenSys\GenerateBundle\Model;

class TestMethod
{
    /** @var string */
    private $name;
    /** @var array */
    private $mockDependencies;
    /** @var array */
    private $propertyMethodCalls;

    public function __construct(
        $name,
        array $mockDependencies,
        array $propertyMethodCalls
    ) {
        $this->name = $name;
        $this->mockDependencies = $mockDependencies;
        $this->propertyMethodCalls = $propertyMethodCalls;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getMockDependencies(): array
    {
        return $this->mockDependencies;
    }

    /**
     * @return array
     */
    public function getPropertyMethodCalls(): array
    {
        return $this->propertyMethodCalls;
    }
}
