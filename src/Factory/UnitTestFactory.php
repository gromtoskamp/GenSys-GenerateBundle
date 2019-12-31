<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\UnitTest;
use ReflectionClass;
use ReflectionException;

class UnitTestFactory
{
    /** @var string  */
    private const NAMESPACE_PREFIX = 'Tests\\Unit\\';

    /** @var MockDependencyFactory */
    private $mockDependencyFactory;
    /** @var TestMethodFactory */
    private $testMethodFactory;

    public function __construct(
        MockDependencyFactory $mockDependencyFactory,
        TestMethodFactory $testMethodFactory
    ) {
        $this->mockDependencyFactory = $mockDependencyFactory;
        $this->testMethodFactory = $testMethodFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return UnitTest
     * @throws ReflectionException
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): UnitTest
    {
        $testMethods = $this->testMethodFactory->createFromReflectionClass($reflectionClass);
        $mockDependencyCollection = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);

        return new UnitTest(
            self::NAMESPACE_PREFIX . $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName() . 'Test',
            $reflectionClass->getName(),
            $mockDependencyCollection->getAll(),
            $testMethods
        );
    }

}
