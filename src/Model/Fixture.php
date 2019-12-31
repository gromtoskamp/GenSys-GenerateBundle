<?php

namespace GenSys\GenerateBundle\Model;

class Fixture
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $className;
    /** @var string */
    private $methodName;
    /** @var array */
    private $mockDependencies;
    /** @var string */
    private $methodParameters;

    /**
     * Fixture constructor.
     * @param string $namespace
     * @param string $className
     * @param string $methodName
     * @param array $mockDependencies
     * @param string $methodParameters
     */
    public function __construct(
        string $namespace,
        string $className,
        string $methodName,
        iterable $mockDependencies,
        string $methodParameters
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->methodName = $methodName;
        $this->mockDependencies = $mockDependencies;
        $this->methodParameters = $methodParameters;
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
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return string
     */
    public function getMethodParameters(): string
    {
        return $this->methodParameters;
    }

    /**
     * @return string
     */
    public function getFixtureArguments(): string
    {
        $propertyReferences = [];
        foreach ($this->getMockDependencies() as $mockDependency) {
            $propertyReferences[] = '$this->' . $mockDependency->getPropertyName();
        }

        return implode(', ', $propertyReferences);
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
