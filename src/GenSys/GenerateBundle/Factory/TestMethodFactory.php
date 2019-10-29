<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Model\Scanner\MethodScanner;
use ReflectionClass;
use ReflectionMethod;

class TestMethodFactory
{
    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    public function __construct(
        MockDependencyFactory $mockDependencyFactory
    ) {
        $this->mockDependencyFactory = $mockDependencyFactory;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return TestMethod
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, array $classMockDependencies): TestMethod
    {
        $methodMockDependencies = $this->mockDependencyFactory->createFromReflectionMethod($reflectionMethod);

        $methodScanner = new MethodScanner($reflectionMethod);
        foreach ($methodScanner->getPropertyReferences() as $propertyCall) {
            foreach ($classMockDependencies as $classMockDependency) {
                if ($classMockDependency->getPropertyName() === $propertyCall) {
                    $methodMockDependencies[$classMockDependency->getFullyQualifiedClassName()] = $classMockDependency;
                }
            }
        }

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $methodMockDependencies,
            $methodScanner->getPropertyMethodCalls()
        );
    }

    public function createFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        $mockDependencies = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);

        $testMethods = [];
        foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            $testMethods[] = $this->createFromReflectionMethod($reflectionMethod, $mockDependencies);
        }

        return $testMethods;
    }
}
