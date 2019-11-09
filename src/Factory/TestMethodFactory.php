<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Fixture;
use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Repository\MockDependencyRepository;
use GenSys\GenerateBundle\Service\Reflection\ClassService;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class TestMethodFactory
{
    /** @var MethodCallFactory */
    private $methodCallFactory;
    /** @var ClassService */
    private $classService;
    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    public function __construct(
        MethodCallFactory $methodCallFactory,
        ClassService $classService,
        MockDependencyFactory $mockDependencyFactory
    ) {
        $this->methodCallFactory = $methodCallFactory;
        $this->classService = $classService;
        $this->mockDependencyFactory = $mockDependencyFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public function createFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        $mockDependencyRepository = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);

        $testMethods = [];
        foreach($this->classService->getPublicNonMagicMethods($reflectionClass) as $reflectionMethod) {
            $testMethods[] = $this->createFromReflectionMethod($reflectionMethod, $mockDependencyRepository);
        }

        return $testMethods;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyRepository $mockDependencyRepository
     * @return TestMethod
     * @throws ReflectionException
     */
    private function createFromReflectionMethod(ReflectionMethod $reflectionMethod, MockDependencyRepository $mockDependencyRepository): TestMethod
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
        $mockDependencies = null !== $constructor ? $mockDependencyRepository->getByReflectionMethod($constructor) : [];

        $fixture = new Fixture(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName(),
            $reflectionMethod->getName(),
            $mockDependencies,
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