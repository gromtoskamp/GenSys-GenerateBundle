<?php

namespace GenSys\GenerateBundle\Repository;

use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionMethod;
use RuntimeException;

/**
 * Class MockDependencyRepository
 * @package GenSys\GenerateBundle\Repository
 */
class MockDependencyRepository
{
    /** @var MockDependency[] $mockDependencies */
    private $mockDependencies = [];

    /**
     * @param MockDependency $mockDependency
     * @param ReflectionMethod $reflectionMethod
     */
    public function add(MockDependency $mockDependency, ReflectionMethod $reflectionMethod): void
    {
        $reflectionMethodName = $reflectionMethod->getName();

        $this->$reflectionMethodName[] = $mockDependency;
        $this->mockDependencies[$mockDependency->getClassName()] = $mockDependency;
    }

    /**
     * @param string $propertyName
     * @return MockDependency
     */
    public function getByPropertyCall(string $propertyName): MockDependency
    {
        foreach ($this->getAll() as $mockDependency) {
            if ($mockDependency->getPropertyName() === $propertyName) {
                return $mockDependency;
            }
        }

        throw new RuntimeException(sprintf('MockDependency not found by propertyCall %s', $propertyName));//TODO: create MockDependencyNotFoundException
    }

    /**
     * @return MockDependency[]
     */
    public function getAll(): array
    {
        return $this->mockDependencies;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return MockDependency[]
     */
    public function getByReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $reflectionMethodName = $reflectionMethod->getName();
        if (!isset($this->$reflectionMethodName)) {
            return [];
        }
        return $this->$reflectionMethodName;
    }
}
