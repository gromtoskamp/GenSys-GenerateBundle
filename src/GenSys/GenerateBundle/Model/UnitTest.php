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

    public function getFixture(): Fixture
    {
        return $this->fixture;
    }
}
