<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\PropertyMethodCall;
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

        $propertyMethodCalls = [];
        foreach ($methodScanner->getPropertyMethodCalls() as $property => $methodCalls) {
            foreach ($methodCalls as $methodCall) {
                $propertyMethodCalls[] = new PropertyMethodCall($property, $methodCall);
            }
        }

        $methodMockDependencies = $mockDependencyRepository->getByReflectionMethod($reflectionMethod);

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $methodMockDependencies,
            $propertyMethodCalls
        );
    }
}
