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
    /** @var TestMethod[] */
    private $testMethods;
    /** @var Fixture */
    private $fixture;

    public function __construct(
        string $namespace,
        string $className,
        array $mockDependencies,
        array $testMethods,
        Fixture $fixture
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->mockDependencies = $mockDependencies;
        $this->testMethods = $testMethods;
        $this->fixture = $fixture;
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
    public function getFullyQualifiedName(): string
    {
        return $this->namespace . '\\' . $this->className;
    }

    /**
     * @return TestMethod[]
     */
    public function getTestMethods(): array
    {
        return $this->testMethods;
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
    public function getNewFixture(): string
    {
        $propertyReferences = [];
        foreach ($this->fixture->getMockDependencies() as $mockDependency) {
            $propertyReferences[] = '$this->' . $mockDependency->getPropertyName();
        }

        return 'new ' . $this->getFixtureClassName() . '(' . implode(', ', $propertyReferences) . ')';
    }

    /**
     * @return string
     */
    public function getFixtureNameSpace(): string
    {
        return $this->fixture->getNamespace();
    }

    /**
     * @return string
     */
    public function getFixtureClassName(): string
    {
        return $this->fixture->getClassName();
    }

    /**
     * @return string
     */
    public function getFixtureFullyQualifiedClassName(): string
    {
        return $this->fixture->getFullyQualifiedClassName();
    }
}
