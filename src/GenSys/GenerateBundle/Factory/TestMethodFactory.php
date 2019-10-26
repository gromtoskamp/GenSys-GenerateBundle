<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MockDependency;
use GenSys\GenerateBundle\Model\TestMethod;
use ReflectionClass;
use ReflectionMethod;

class TestMethodFactory
{
    const REGEX = '/\$this->([a-zA-Z0-9_]*)->[a-zA-Z0-9_]*\(/';

    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    public function __construct(
        MockDependencyFactory $mockDependencyFactory
    ) {
        $this->mockDependencyFactory = $mockDependencyFactory;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependency[] $classMockDependencies
     * @return TestMethod
     */
    public function createFromSourceReflectionMethod(ReflectionMethod $reflectionMethod, array $classMockDependencies): TestMethod
    {
        $methodMockDependencies = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            if (null === $parameterClass) {
                continue;
            }

            $classMockDependency = $classMockDependencies[$parameterClass->getName()];
            if (isset($classMockDependency)) {
                $methodMockDependencies[$classMockDependency->getFullyQualifiedClassName()] = $classMockDependency;
            }
        }


        $reflectionMethodBody = $this->getReflectionMethodBody($reflectionMethod);

        $matches = [];
        preg_match_all(self::REGEX, $reflectionMethodBody, $matches);
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

    private function getReflectionMethodBody(ReflectionMethod $reflectionMethod)
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine() + 1;
        $endLine = $reflectionMethod->getEndLine() - 1;
        $length = $endLine - $startLine;

        $source = file($filename);
        return implode("", array_slice($source, $startLine, $length));
    }
}
