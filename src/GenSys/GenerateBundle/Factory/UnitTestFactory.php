<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\UnitTest;
use ReflectionClass;

class UnitTestFactory
{
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

    public function createFromSourceReflectionClass(ReflectionClass $reflectionClass): UnitTest
    {
        $mockDependencies = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);
        $testMethods = $this->testMethodFactory->createFromReflectionClass($reflectionClass);

        return new UnitTest(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName() . 'Test',
            $mockDependencies,
            $testMethods
        );
    }

}
