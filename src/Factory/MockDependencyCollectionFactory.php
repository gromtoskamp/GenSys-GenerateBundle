<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Collection\MockDependencyCollection;
use ReflectionClass;
use ReflectionMethod;

class MockDependencyCollectionFactory
{
    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    /**
     * MockDependencyCollectionFactory constructor.
     * @param MockDependencyFactory $mockDependencyFactory
     */
    public function __construct(
        MockDependencyFactory $mockDependencyFactory
    ) {
        $this->mockDependencyFactory = $mockDependencyFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return MockDependencyCollection
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): MockDependencyCollection
    {
        $mockDependencyCollection = new MockDependencyCollection();
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $this->createFromReflectionMethod($reflectionMethod, $mockDependencyCollection);
        }

        return $mockDependencyCollection;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyCollection $mockDependencyCollection
     * @return MockDependencyCollection
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyCollection $mockDependencyCollection): MockDependencyCollection
    {
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            if (null === $parameterClass) {
                continue;
            }

            $mockDependency = $this->mockDependencyFactory->create($parameter);
            $mockDependencyCollection->add($mockDependency, $reflectionMethod);
        }

        return $mockDependencyCollection;
    }

}
