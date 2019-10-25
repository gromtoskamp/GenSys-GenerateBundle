<?php

namespace GenSys\GenerateBundle\Model\BluePrint;

use GenSys\GenerateBundle\Model\BluePrint;
use GenSys\GenerateBundle\Model\MockDependency;
use Nette\PhpGenerator\Method;
use ReflectionMethod;

class TestMethod
{
    /** @var string regex pattern to find propertyName in $this->propertyName->methodCall() */
    const REGEX = '/\$this->([a-zA-Z0-9_]*)->[a-zA-Z0-9_]*\(/';

    /** @var Method */
    private $netteMethod;

    /** @var string */
    private $name;

    /** @var string */
    private $body;

    /** @var MockDependency[] */
    private $mockDependencies = [];

    public function __construct(ReflectionMethod $reflectionMethod, BluePrint $bluePrint)
    {
        $testName = 'test' . ucfirst($reflectionMethod->getName());
        $this->netteMethod = new Method($testName);
        $this->setName($testName);

        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            if (null === $parameterClass) {
                continue;
            }
            $mockDependency = $bluePrint->getMockDependencies()['\\' . $parameterClass->getName()];
            if (isset($mockDependency)) {
                $this->addMockDependency($mockDependency);
            }
        }


        $reflectionMethodBody = $this->getReflectionMethodBody($reflectionMethod);

        $matches =[];
        preg_match_all(self::REGEX, $reflectionMethodBody, $matches);
        $uniqueMatches = array_unique($matches[1]);
        foreach ($uniqueMatches as $match) {
            foreach ($bluePrint->getMockDependencies() as $mockDependency) {
                if ($mockDependency->getPropertyName() === $match) {
                    $this->addMockDependency($mockDependency);
                }
            }
        }


        foreach ($this->getMockDependencies() as $mockDependency) {
            $this->netteMethod->addBody($mockDependency->getVariableName() . ' = clone ' . $mockDependency->getPropertyCall() . ';');
        }
    }

    public function getNetteMethod()
    {
        return $this->netteMethod;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    private function getMockDependencies()
    {
        return $this->mockDependencies;
    }

    private function addMockDependency(MockDependency $mockDependency)
    {
     //   $this->netteMethod->addBody()
        $this->mockDependencies[$mockDependency->getFullyQualifiedClassName()] = $mockDependency;
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
