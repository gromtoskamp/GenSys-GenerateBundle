<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MethodCall;
use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Model\Scanner\MethodScanner;
use GenSys\GenerateBundle\Service\MockDependencyRepository;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;

class TestMethodFactory
{
    /**
     * @param ReflectionClass $reflectionClass
     * @param MockDependencyRepository $mockDependencyRepository
     * @return array
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass, MockDependencyRepository $mockDependencyRepository): array
    {
        if (empty($mockDependencyRepository->getAll())) {
            throw new InvalidArgumentException('MockDependencyRepository should be instantiated with mockdependencies');
        }

        $testMethods = [];
        foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            $testMethods[] = $this->createFromReflectionMethod($reflectionMethod, $mockDependencyRepository);
        }

        return $testMethods;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyRepository $mockDependencyRepository
     * @return TestMethod
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyRepository $mockDependencyRepository): TestMethod
    {
        $methodScanner = new MethodScanner($reflectionMethod);
        foreach ($methodScanner->getPropertyReferences() as $propertyName) {
            $mockDependencyRepository->add($mockDependencyRepository->getByPropertyCall($propertyName), $reflectionMethod);
        }

        $methodCalls = [];
        foreach ($methodScanner->getPropertyCalls() as $property => $propertyCalls) {
            foreach ($propertyCalls as $propertyCall) {
                $methodCalls[] = new MethodCall($property, $propertyCall);
            }
        }

        foreach ($methodScanner->getParameterCalls() as $parameter => $parameterCalls) {
            foreach ($parameterCalls as $parameterCall) {
                $methodCalls[] = new MethodCall($parameter, $parameterCall);
            }
        }

        $methodMockDependencies = $mockDependencyRepository->getByReflectionMethod($reflectionMethod);

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $methodMockDependencies,
            $methodCalls
        );
    }
}
