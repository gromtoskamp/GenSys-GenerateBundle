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
    /** @var FixtureFactory */
    private $fixtureFactory;
    /** @var ClassService */
    private $classService;

    /**
     * UnitTestFactory constructor.
     * @param MockDependencyCollectionFactory $mockDependencyCollectionFactory
     * @param TestMethodFactory $testMethodFactory
     * @param FixtureFactory $fixtureFactory
     * @param ClassService $classService
     */
    public function __construct(
        MockDependencyCollectionFactory $mockDependencyCollectionFactory,
        TestMethodFactory $testMethodFactory,
        FixtureFactory $fixtureFactory,
        ClassService $classService
    ) {
        $this->mockDependencyCollectionFactory = $mockDependencyCollectionFactory;
        $this->testMethodFactory = $testMethodFactory;
        $this->fixtureFactory = $fixtureFactory;
        $this->classService = $classService;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return UnitTest
     * @throws ReflectionException
     */
    public function create(ReflectionClass $reflectionClass): UnitTest
    {
        $mockDependencyCollection = $this->mockDependencyCollectionFactory->createFromReflectionClass($reflectionClass);
        $fixture = $this->fixtureFactory->create($reflectionClass, $mockDependencyCollection);

        $testMethods = [];
        $reflectionMethods = $this->classService->getPublicNonMagicMethods($reflectionClass);
        foreach ($reflectionMethods as $reflectionMethod) {
            $testMethods[] = $this->testMethodFactory->createFromReflectionMethod($reflectionMethod);
        }

        return new UnitTest(
            self::NAMESPACE_PREFIX . $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName() . 'Test',
            $reflectionClass->getName(),
            $mockDependencyCollection->getAll(),
            $testMethods,
            $fixture
        );
    }

}
