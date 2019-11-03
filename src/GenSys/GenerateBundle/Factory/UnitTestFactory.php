<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Fixture;
use GenSys\GenerateBundle\Model\UnitTest;
use ReflectionClass;
use ReflectionException;

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
     * @param string $className
     * @return UnitTest
     * @throws ReflectionException
     */
    public function createFromClassName(string $className): UnitTest
    {
        $reflectionClass = new ReflectionClass($className);
        $mockDependencyRepository = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);
        $testMethods = $this->testMethodFactory->createFromReflectionClass($reflectionClass, $mockDependencyRepository);
        $fixture = new Fixture(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName(),
            $mockDependencyRepository->getByReflectionMethod($reflectionClass->getConstructor())
        );

        return new UnitTest(
            'Tests\\Unit\\' . $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName() . 'Test',
            $mockDependencyRepository->getAll(),
            $testMethods,
            $fixture
        );
    }

}
