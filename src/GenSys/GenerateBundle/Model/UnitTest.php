<?php

namespace GenSys\GenerateBundle\Model;

use GenSys\GenerateBundle\Service\MockDependencyRepository;

class UnitTest
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $className;
    /** @var MockDependencyRepository */
    private $mockDependencyRepository;
    /** @var array */
    private $testMethods;

    public function __construct(
        string $namespace,
        string $className,
        MockDependencyRepository $mockDependencyRepository,
        array $testMethods
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->mockDependencyRepository = $mockDependencyRepository;
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
        return $this->mockDependencyRepository->getAll();
    }

    /**
     * @return TestMethod[]
     */
    public function getTestMethods(): array
    {
        return $this->testMethods;
    }
}
