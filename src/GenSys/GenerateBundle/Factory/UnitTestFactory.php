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

    /**
     * @param ReflectionClass $reflectionClass
     * @return UnitTest
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): UnitTest
    {
        $testMethods = $this->testMethodFactory->createFromReflectionClass($reflectionClass);
        $mockDependencyRepository = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);

        return new UnitTest(
            'Tests\\Unit\\' . $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName() . 'Test',
            $reflectionClass->getName(),
            $mockDependencyRepository->getAll(),
            $testMethods
        );
    }

}
