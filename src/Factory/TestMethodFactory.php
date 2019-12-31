<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Collection\MockDependencyCollection;
use GenSys\GenerateBundle\Service\Decorator\MethodCallSorter;
use GenSys\GenerateBundle\Model\Fixture;
use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Service\Reflection\ClassService;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class TestMethodFactory
{
    private const RETURN_VOID = 'void';

    /** @var MethodCallFactory */
    private $methodCallFactory;
    /** @var ClassService */
    private $classService;
    /** @var MockDependencyFactory */
    private $mockDependencyFactory;
    /** @var MethodCallSorter */
    private $methodCallSorter;

    public function __construct(
        MethodCallFactory $methodCallFactory,
        ClassService $classService,
        MockDependencyFactory $mockDependencyFactory,
        MethodCallSorter $methodCallSorter
    ) {
        $this->methodCallFactory = $methodCallFactory;
        $this->classService = $classService;
        $this->mockDependencyFactory = $mockDependencyFactory;
        $this->methodCallSorter = $methodCallSorter;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     * @throws ReflectionException
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        $mockDependencyCollection = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);

        $testMethods = [];
        foreach($this->classService->getPublicNonMagicMethods($reflectionClass) as $reflectionMethod) {
            $testMethods[] = $this->createFromReflectionMethod($reflectionMethod, $mockDependencyCollection);
        }

        return $testMethods;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyCollection $mockDependencyCollection
     * @return TestMethod
     * @throws ReflectionException
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyCollection $mockDependencyCollection): TestMethod
    {
        $methodCalls = $this->methodCallFactory->createFromReflectionMethod($reflectionMethod);
        $reflectionClass = $reflectionMethod->getDeclaringClass();

        $parameters = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            if ($parameter->getClass()) {
                $parameters[] = '$this->' . lcfirst($parameter->getClass()->getShortName());
            } else {
                $parameters[] = 'null';
            }
        }

        $constructor = $reflectionClass->getConstructor();
        $mockDependencies = null !== $constructor ? $mockDependencyCollection->getByReflectionMethod($constructor) : [];

        $fixture = new Fixture(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName(),
            $reflectionMethod->getName(),
            $mockDependencies,
            implode(',', $parameters)
        );

        $methodCalls = $this->methodCallSorter->decorate($methodCalls);
        $returnsVoid = $this->getReturnsVoid($reflectionMethod);
        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $reflectionMethod->getName(),
            $returnsVoid,
            $methodCalls,
            $fixture
        );
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return bool
     */
    private function getReturnsVoid(ReflectionMethod $reflectionMethod): bool
    {
        $returnType = $reflectionMethod->getReturnType();
        if (null === $returnType) {
            return false;
        }

        $returnTypeName = $returnType->getName();
        return $returnTypeName === self::RETURN_VOID;
    }
}
