<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MockDependency;
use GenSys\GenerateBundle\Service\MockDependencyRepository;
use ReflectionClass;
use ReflectionMethod;

class MockDependencyFactory
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return MockDependencyRepository
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): MockDependencyRepository
    {
        $mockDependencyRepository = new MockDependencyRepository();
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $this->createFromReflectionMethod($reflectionMethod, $mockDependencyRepository);
        }

        return $mockDependencyRepository;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyRepository $mockDependencyRepository
     * @return MockDependencyRepository
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyRepository $mockDependencyRepository): MockDependencyRepository
    {
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            if (null === $parameterClass) {
                continue;
            }

            $mockDependencyRepository->add(new MockDependency($parameter), $reflectionMethod);
        }

        return $mockDependencyRepository;
    }

}
