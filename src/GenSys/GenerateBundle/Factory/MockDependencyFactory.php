<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class MockDependencyFactory
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        $mockDependencies = [];
        foreach ($reflectionClass->getConstructor()->getParameters() as $parameter) {
            $mockDependencies[$parameter->getClass()->getName()] = $this->createFromReflectionParameter($parameter);
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

            $mockDependency = $this->createFromReflectionParameter($parameter);
            $mockDependencies[$parameterClass->getName()] = $mockDependency;
        }

        return $mockDependencies;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return MockDependency
     */
    private function createFromReflectionParameter(ReflectionParameter $parameter)
    {
        return new MockDependency($parameter);
    }
}
