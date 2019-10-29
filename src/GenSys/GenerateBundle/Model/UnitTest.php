<?php

namespace GenSys\GenerateBundle\Model;

class UnitTest
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $className;
    /** @var MockDependency[] */
    private $mockDependencies;
    /** @var array */
    private $testMethods;

    public function __construct(
        string $namespace,
        string $className,
        array $mockDependencies,
        array $testMethods
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->mockDependencies = $mockDependencies;
        $this->testMethods = $testMethods;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return MockDependency[]
     */
    public function getMockDependencies(): array
    {
        return $this->mockDependencies;
    }

    /**
     * @return TestMethod[]
     */
    public function getTestMethods(): array
    {
        return $this->testMethods;
    }
}
