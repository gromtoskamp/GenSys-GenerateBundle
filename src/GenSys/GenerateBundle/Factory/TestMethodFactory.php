<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Fixture;
use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Service\MockDependencyRepository;
use ReflectionClass;
use ReflectionMethod;

class TestMethodFactory
{
    /** @var MethodCallFactory */
    private $methodCallFactory;
    /** @var MethodScannerFactory */
    private $methodScannerFactory;

    public function __construct(
        MethodCallFactory $methodCallFactory,
        MethodScannerFactory $methodScannerFactory
    ) {
        $this->methodCallFactory = $methodCallFactory;
        $this->methodScannerFactory = $methodScannerFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass, MockDependencyRepository $mockDependencyRepository): array
    {
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
     * @return TestMethod
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyRepository $mockDependencyRepository): TestMethod
    {
        $methodScanner = $this->methodScannerFactory->createFromReflectionMethod($reflectionMethod);
        $methodCalls = $this->methodCallFactory->createFromMethodScanner($methodScanner);
        $reflectionClass = $reflectionMethod->getDeclaringClass();

        $parameters = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            if ($parameter->getClass()) {
                $parameters[] = '$this->' . lcfirst($parameter->getClass()->getShortName());
            } else {
                $parameters[] = 'null';
            }
        }

        $fixture = new Fixture(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName(),
            $reflectionMethod->getName(),
            $mockDependencyRepository->getByReflectionMethod($reflectionClass->getConstructor()),
            implode(',', $parameters)
        );

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $reflectionMethod->getName(),
            $methodCalls,
            $fixture
        );
    }
}
