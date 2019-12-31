<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Collection\MockDependencyCollection;
use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionClass;
use ReflectionMethod;

class MockDependencyCollectionFactory
{
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

            $mockDependencyCollection->add(new MockDependency($parameter), $reflectionMethod);
        }

        return $mockDependencyCollection;
    }

}
