<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\UnitTest;
use GenSys\GenerateBundle\Service\Reflection\ClassService;
use ReflectionClass;
use ReflectionException;

class UnitTestFactory
{
    /** @var string  */
    private const NAMESPACE_PREFIX = 'Tests\\Unit\\';

    /** @var MockDependencyCollectionFactory */
    private $mockDependencyCollectionFactory;
    /** @var TestMethodFactory */
    private $testMethodFactory;
    /** @var ClassService */
    private $classService;

    public function __construct(
        MockDependencyCollectionFactory $mockDependencyCollectionFactory,
        TestMethodFactory $testMethodFactory,
        ClassService $classService
    ) {
        $this->mockDependencyCollectionFactory = $mockDependencyCollectionFactory;
        $this->testMethodFactory = $testMethodFactory;
        $this->classService = $classService;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return UnitTest
     * @throws ReflectionException
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): UnitTest
    {
        $mockDependencyCollection = $this->mockDependencyCollectionFactory->createFromReflectionClass($reflectionClass);

        $testMethods = [];
        $reflectionMethods = $this->classService->getPublicNonMagicMethods($reflectionClass);
        foreach ($reflectionMethods as $reflectionMethod) {
            $testMethods[] = $this->testMethodFactory->createFromReflectionMethod($reflectionMethod, $mockDependencyCollection);
        }

        return new UnitTest(
            self::NAMESPACE_PREFIX . $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName() . 'Test',
            $reflectionClass->getName(),
            $mockDependencyCollection->getAll(),
            $testMethods
        );
    }

}
