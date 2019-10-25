<?php

namespace GenSys\GenerateBundle\Model;

use GenSys\GenerateBundle\Model\BluePrint\TestMethod;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class BluePrint
{
    const METHOD_SETUP = 'setUp';

    /** @var PhpNamespace */
    private $phpNamespace;

    /** @var ClassType */
    private $classType;

    /** @var MockDependency[] */
    private $mockDependencies = [];

    public function __construct(string $fullyQualifiedName)
    {
        $namespaceArray = explode('\\', $fullyQualifiedName);
        $className = array_pop($namespaceArray) . 'Test';
        $namespace = implode('\\', $namespaceArray);

        $this->setNamespace($namespace);
        $this->setClass($className);

        $this->classType->addMethod(self::METHOD_SETUP);
    }

    public function getPhpNamespace()
    {
        return $this->phpNamespace;
    }
    
    public function setNamespace(string $namespace)
    {
        $this->phpNamespace = new PhpNamespace($namespace);
    }

    public function addUse(string $useClassName)
    {
        $this->phpNamespace->addUse($useClassName);
    }

    public function setClass(string $className)
    {
        $this->classType = $this->phpNamespace->addClass($className);
    }

    public function setExtend(string $extendClassName)
    {
        $this->classType->addExtend($extendClassName);
    }

    public function addTestMethod(TestMethod $testMethod)
    {
        $this->classType->addMethod(
            $testMethod->getNetteMethod()->getName()
        )->addBody(
            $testMethod->getNetteMethod()->getBody()
        );
    }

    public function getMockDependencies()
    {
        return $this->mockDependencies;
    }

    public function addMockDependencies(array $mockDependencies)
    {
        foreach ($mockDependencies as $mockDependency) {
            $this->addMockDependency($mockDependency);
        }
    }

    public function addMockDependency(MockDependency $mockDependency)
    {
        $this->classType->getMethod(self::METHOD_SETUP)
            ->addBody($mockDependency->getBody());
        $this->classType->addProperty($mockDependency->getPropertyName())
            ->addComment($mockDependency->getDocBlock());
        $this->phpNamespace->addUse($mockDependency->getFullyQualifiedClassName());
        $this->mockDependencies[$mockDependency->getFullyQualifiedClassName()] = $mockDependency;
    }
}
