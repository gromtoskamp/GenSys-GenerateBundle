<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Service\MethodScanner;
use ReflectionClass;
use ReflectionMethod;

class TestMethodFactory
{
    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    /** @var MethodScanner */
    private $methodScanner;

    public function __construct(
        MockDependencyFactory $mockDependencyFactory,
        MethodScanner $methodScanner
    ) {
        $this->mockDependencyFactory = $mockDependencyFactory;
        $this->methodScanner = $methodScanner;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return TestMethod
     */
    private function createFromSourceReflectionMethod(ReflectionMethod $reflectionMethod): TestMethod
    {
        $classMockDependencies = $this->mockDependencyFactory->createFromReflectionClass($reflectionMethod->getDeclaringClass());
        $methodMockDependencies = $this->mockDependencyFactory->createFromReflectionMethod($reflectionMethod);

        foreach ($this->methodScanner->getPropertyCalls($reflectionMethod) as $propertyCall) {
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

        foreach ($this->methodScanner->getPropertyMethodCalls($reflectionMethod) as $property => $methodCalls) {
            foreach ($methodCalls as $methodCall) {
                $body[] = '$' . $property . "->expects(\$this->any())";
                $body[] = "    ->method('" . $methodCall . "')";
                $body[] = "    ->willReturn(null);";
            }
        }

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $body
        );
    }

    public function createFromSourceReflectionClass(ReflectionClass $reflectionClass): array
    {
        $testMethods = [];
        foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            $testMethods[] = $this->createFromSourceReflectionMethod($reflectionMethod);
        }

        return $testMethods;
    }
}
