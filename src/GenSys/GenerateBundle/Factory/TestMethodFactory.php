<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MockDependency;
use GenSys\GenerateBundle\Model\TestMethod;
use GenSys\GenerateBundle\Service\FileService;
use ReflectionClass;
use ReflectionMethod;

class TestMethodFactory
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';

    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    /** @var FileService */
    private $fileService;

    public function __construct(
        MockDependencyFactory $mockDependencyFactory,
        FileService $fileService
    ) {
        $this->mockDependencyFactory = $mockDependencyFactory;
        $this->fileService = $fileService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependency[] $classMockDependencies
     * @return TestMethod
     */
    public function createFromSourceReflectionMethod(ReflectionMethod $reflectionMethod, array $classMockDependencies): TestMethod
    {
        $methodMockDependencies = $this->mockDependencyFactory->createFromReflectionMethod($reflectionMethod);
        $reflectionMethodBody = $this->fileService->getReflectionMethodBody($reflectionMethod);

        $matches = [];
        preg_match_all(self::REGEX_PROPERTY_REFERENCE, $reflectionMethodBody, $matches);
        $uniqueMatches = array_unique($matches[1]);


        foreach ($uniqueMatches as $match) {
            foreach ($classMockDependencies as $classMockDependency) {
                if ($classMockDependency->getPropertyName() === $match) {
                    $methodMockDependencies[$classMockDependency->getFullyQualifiedClassName()] = $classMockDependency;
                }
            }
        }


        $body = [];
        foreach ($methodMockDependencies as $mockDependency) {
            $body[] = $mockDependency->getVariableName() . ' = clone ' . $mockDependency->getPropertyCall() . ';';
        }

        return new TestMethod(
            'test' . ucfirst($reflectionMethod->getName()),
            $body
        );
    }

    public function createFromSourceReflectionClass(ReflectionClass $reflectionClass)
    {
        $mockDependencies = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);

        $testMethods = [];
        foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            $testMethods[] = $this->createFromSourceReflectionMethod($reflectionMethod, $mockDependencies);
        }

        return $testMethods;
    }


}
