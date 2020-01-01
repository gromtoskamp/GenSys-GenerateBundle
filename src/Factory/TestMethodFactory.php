<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Collection\MockDependencyCollection;
use GenSys\GenerateBundle\Service\Decorator\MethodCallSorter;
use GenSys\GenerateBundle\Model\TestMethod;
use ReflectionException;
use ReflectionMethod;

class TestMethodFactory
{
    /** @var string */
    private const RETURN_VOID = 'void';

    /** @var MethodCallFactory */
    private $methodCallFactory;
    /** @var MethodCallSorter */
    private $methodCallSorter;
    /** @var FixtureFactory */
    private $fixtureFactory;

    /**
     * TestMethodFactory constructor.
     * @param MethodCallFactory $methodCallFactory
     * @param MethodCallSorter $methodCallSorter
     * @param FixtureFactory $fixtureFactory
     */
    public function __construct(
        MethodCallFactory $methodCallFactory,
        MethodCallSorter $methodCallSorter,
        FixtureFactory $fixtureFactory
    ) {
        $this->methodCallFactory = $methodCallFactory;
        $this->methodCallSorter = $methodCallSorter;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyCollection $mockDependencyCollection
     * @return TestMethod
     * @throws ReflectionException
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyCollection $mockDependencyCollection): TestMethod
    {
        $fixture = $this->fixtureFactory->create(
            $reflectionMethod,
            $mockDependencyCollection
        );

        $methodCalls = $this->methodCallFactory->createFromReflectionMethod($reflectionMethod);
        $methodCalls = $this->methodCallSorter->sort($methodCalls);
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
