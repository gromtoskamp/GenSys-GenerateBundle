<?php

namespace GenSys\GenerateBundle\Model;

class Fixture
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $className;
    /** @var array */
    private $mockDependencies;

    public function __construct(
        string $namespace,
        string $className,
        array $mockDependencies
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->mockDependencies = $mockDependencies;
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
     * @return string
     */
    public function getFullyQualifiedClassName(): string
    {
        return $this->namespace . '\\' . $this->className;
    }
}
