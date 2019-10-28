<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionClass;
use ReflectionMethod;

class MockDependencyFactory
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return MockDependency[]
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        $mockDependencies = [];
        foreach ($reflectionClass->getConstructor()->getParameters() as $parameter) {
            $mockDependencies[$parameter->getClass()->getName()] = new MockDependency($parameter);
        }

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            foreach ($this->createFromReflectionMethod($reflectionMethod) as $key => $methodMockDependency) {
                $mockDependencies[$key] = $methodMockDependency;
            }
        }

        return $mockDependencies;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return MockDependency[]
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $mockDependencies = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            if (null === $parameterClass) {
                continue;
            }

            $mockDependencies[$parameterClass->getName()] = new MockDependency($parameter);
        }

        return $mockDependencies;
    }

}
