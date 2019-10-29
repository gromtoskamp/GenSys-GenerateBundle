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
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod): TestMethod
    {
        $classMockDependencies = $this->mockDependencyFactory->createFromReflectionClass($reflectionMethod->getDeclaringClass());
        $methodMockDependencies = $this->mockDependencyFactory->createFromReflectionMethod($reflectionMethod);

        $methodScanner = new MethodScanner($reflectionMethod);
        foreach ($methodScanner->getPropertyReferences() as $propertyCall) {
            foreach ($classMockDependencies as $classMockDependency) {
                if ($classMockDependency->getPropertyName() === $propertyCall) {
                    $methodMockDependencies[$classMockDependency->getFullyQualifiedClassName()] = $classMockDependency;
                }
            }
        }

        $body = [];
        foreach ($methodMockDependencies as $mockDependency) {
            $body[] = $mockDependency->getVariableName() . ' = clone ' . $mockDependency->getPropertyCall() . ';';
        }

        foreach ($methodScanner->getPropertyMethodCalls() as $property => $methodCalls) {
            array_push($body, ...$this->getBodyFromPropertyMethodCalls($property, $methodCalls));
        }

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $body
        );
    }

    public function createFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        $testMethods = [];
        foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            $testMethods[] = $this->createFromReflectionMethod($reflectionMethod);
        }

        return $testMethods;
    }

    private function getBodyFromPropertyMethodCalls($property, $methodCalls)
    {
        $body = [];
        foreach ($methodCalls as $methodCall) {
            $body[] = '$' . $property . "->method('" . $methodCall . "')";
            $body[] = '    ->willReturn(null);';
        }
        return $body;
    }
}
