<?php

namespace GenSys\GenerateBundle\Model;

class UnitTest
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $className;
    /** @var string */
    private $fixtureClassName;
    /** @var MockDependency[] */
    private $mockDependencies;
    /** @var TestMethod[] */
    private $testMethods;
    /** @var Fixture */
    private $fixture;

    /**
     * UnitTest constructor.
     * @param string $namespace
     * @param string $className
     * @param string $fixtureClassName
     * @param array $mockDependencies
     * @param array $testMethods
     * @param Fixture $fixture
     */
    public function __construct(
        string $namespace,
        string $className,
        string $fixtureClassName,
        array $mockDependencies,
        array $testMethods,
        Fixture $fixture
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->fixtureClassName = $fixtureClassName;
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
    public function getFixtureClassName(): string
    {
        return $this->fixtureClassName;
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
     * @return Fixture
     */
    public function getFixture(): Fixture
    {
        return $this->fixture;
    }
}
