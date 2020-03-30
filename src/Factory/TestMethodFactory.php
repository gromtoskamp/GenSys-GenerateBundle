<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Service\Decorator\MethodCallSorter;
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

    /**
     * TestMethodFactory constructor.
     * @param MethodCallFactory $methodCallFactory
     * @param MethodCallSorter $methodCallSorter
     */
    public function __construct(
        MethodCallFactory $methodCallFactory,
        MethodCallSorter $methodCallSorter
    ) {
        $this->methodCallFactory = $methodCallFactory;
        $this->methodCallSorter = $methodCallSorter;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return TestMethod
     * @throws ReflectionException
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod): TestMethod
    {
        $methodCalls = $this->methodCallFactory->createFromReflectionMethod($reflectionMethod);
        $methodCalls = $this->methodCallSorter->sort($methodCalls);
        $returnsVoid = $this->getReturnsVoid($reflectionMethod);

        $parameters = $this->getParameters($reflectionMethod);

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $reflectionMethod->getName(),
            $returnsVoid,
            $methodCalls,
            implode(',', $parameters)
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

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    private function getParameters(ReflectionMethod $reflectionMethod): array
    {
        $parameters = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            if ($parameter->getClass()) {
                $parameters[] = '$this->' . lcfirst($parameter->getClass()->getShortName());
            } else {
                $parameters[] = 'null';
            }
        }
        return $parameters;
    }
}
